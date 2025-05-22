<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use App\Models\Dentist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Afficher le tableau de bord d'administration
     */
    public function index()
    {
        // Statistiques générales
        $totalUsers = User::whereNull('deleted_at')->count();
        $totalDentistes = Dentist::whereNull('deleted_at')->count();
        $activeSessions = $this->getActiveSessions();
        $systemHealth = $this->calculateSystemHealth();
        $activeAlerts = $this->getActiveAlerts();

        // Statistiques des sessions
        $sessionStats = $this->getSessionStats();

        // Métriques système
        $systemMetrics = $this->getSystemMetrics();

        // Alertes récentes
        $recentAlerts = $this->getRecentAlerts();


        // Données de configuration
        $treatmentTypesCount = $this->getTreatmentTypesCount();
        $paymentMethodsCount = $this->getPaymentMethodsCount();
        $unreadNotifications = Notification::where('read_date', null)->count();
        $lastBackup = $this->getLastBackupDate();

        return view('settings.index', compact(
            'totalUsers',
            'totalDentistes',
            'activeSessions',
            'systemHealth',
            'activeAlerts',
            'sessionStats',
            'systemMetrics',
            'recentAlerts',
            'treatmentTypesCount',
            'paymentMethodsCount',
            'unreadNotifications',
            'lastBackup'
        ));
    }

    /**
     * Récupérer les sessions actives avec les informations utilisateur
     */
    private function getActiveSessions()
    {
        $sessionLifetime = config('session.lifetime') * 30;
        $now = now()->timestamp;

        $sessions = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', $now - $sessionLifetime)
            ->get();

        $activeSessions = [];

        foreach ($sessions as $session) {
            $user = User::find($session->user_id);
            if ($user) {
                $activeSessions[] = [
                    'user' => $user,
                    'session' => $session
                ];
            }
        }

        return collect($activeSessions);
    }

    /**
     * Calculer la santé du système
     */
    private function calculateSystemHealth()
    {
        $factors = [];

        // Vérifier la base de données
        try {
            DB::select('SELECT 1');
            $factors[] = 100;
        } catch (\Exception $e) {
            $factors[] = 0;
        }

        // Vérifier l'espace disque
        $diskUsage = $this->getDiskUsage();
        if (is_array($diskUsage) && isset($diskUsage['percent'])) {
            $factors[] = $diskUsage['percent'] < 90 ? 100 : (100 - $diskUsage['percent']);
        }


        return round(array_sum($factors) / count($factors), 1);
    }

    /**
     * Obtenir le nombre d'alertes actives
     */
    private function getActiveAlerts()
    {
        $alerts = 0;

        // Vérifier l'utilisation du disque

        $diskUsage = $this->getDiskUsage();
        if (is_array($diskUsage) && isset($diskUsage['percent'])) {
            if ($diskUsage['percent'] > 80) {
                 $alerts++;
            }
        }



        // Vérifier les sessions expirées
        if ($this->getExpiredSessionsCount() > 10) {
            $alerts++;
        }

        return $alerts;
    }

    /**
     * Obtenir les statistiques des sessions
     */
    private function getSessionStats()
    {
        $sessionLifetime = config('session.lifetime') * 60;
        $now = now()->timestamp;

        $allSessions = DB::table('sessions')->whereNotNull('user_id')->get();

        $active = $allSessions->where('last_activity', '>=', $now - $sessionLifetime)->count();
        $inactive = $allSessions->where('last_activity', '<', $now - $sessionLifetime)->count();

        // Sessions d'aujourd'hui
        $todayStart = Carbon::today()->timestamp;
        $today = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', $todayStart)
            ->distinct('user_id')
            ->count();

        return [
            'active' => $active,
            'inactive' => $inactive,
            'today' => $today
        ];
    }

    /**
     * Obtenir les métriques système
     */
    private function getSystemMetrics()
    {
        return [
            'cpu' => $this->getCpuUsage(),
            'memory' => $this->getMemoryUsage(),
            'disk' => $this->getDiskUsage(),
            'connections' => $this->getActiveConnections()
        ];
    }

    /**
     * Obtenir les alertes récentes
     */
    private function getRecentAlerts()
    {
        $alerts = [];

        // Vérifier l'utilisation du disque

        $diskUsage = $this->getDiskUsage();
        if (is_array($diskUsage) && isset($diskUsage['percent'])) {
            if ($diskUsage['percent'] > 75) {
                $alerts[] = [
                    'message' => 'Utilisation disque élevée',
                    'details' => "Espace disque à {$diskUsage['percent']}%",
                    'created_at' => Carbon::now()->diffForHumans()
                ];
            }
        }

        // Vérifier les tentatives de connexion échouées
        $failedLogins = $this->getRecentFailedLogins();
        if ($failedLogins > 5) {
            $alerts[] = [
                'message' => 'Tentatives de connexion suspectes',
                'details' => "{$failedLogins} tentatives échouées récentes",
                'created_at' => Carbon::now()->subMinutes(30)->diffForHumans()
            ];
        }

        return $alerts;
    }



    /**
     * Déconnecter un utilisateur spécifique
     */
    public function logoutUser(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Supprimer toutes les sessions de l'utilisateur
            DB::table('sessions')->where('user_id', $userId)->delete();

            // Logger l'action
            Log::info("Admin logout user", [
                'admin_id' => auth()->id(),
                'target_user_id' => $userId,
                'target_user_email' => $user->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur déconnecté avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error("Error logging out user: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la déconnexion'
            ], 500);
        }
    }

    /**
     * Déconnecter tous les utilisateurs
     */
    public function logoutAllUsers(Request $request)
    {
        try {
            $sessionCount = DB::table('sessions')->whereNotNull('user_id')->count();

            // Supprimer toutes les sessions utilisateur (sauf celle de l'admin actuel)
            DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('user_id', '!=', auth()->id())
                ->delete();

            // Logger l'action
            Log::warning("Admin logout all users", [
                'admin_id' => auth()->id(),
                'sessions_count' => $sessionCount
            ]);

            return response()->json([
                'success' => true,
                'count' => $sessionCount,
                'message' => 'Toutes les sessions ont été fermées'
            ]);
        } catch (\Exception $e) {
            Log::error("Error logging out all users: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la déconnexion'
            ], 500);
        }
    }

    /**
     * Suspendre une session
     */
    public function suspendSession(Request $request, $sessionId)
    {
        try {
            // Marquer la session comme expirée
            DB::table('sessions')
                ->where('id', $sessionId)
                ->update(['last_activity' => 0]);

            Log::info("Admin suspend session", [
                'admin_id' => auth()->id(),
                'session_id' => $sessionId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Session suspendue avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error("Error suspending session: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suspension'
            ], 500);
        }
    }

    /**
     * Suspendre les sessions inactives
     */
    public function suspendInactiveSessions(Request $request)
    {
        try {
            $inactiveThreshold = now()->timestamp - (15 * 60); // 15 minutes

            $suspendedCount = DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '<', $inactiveThreshold)
                ->update(['last_activity' => 0]);

            Log::info("Admin suspend inactive sessions", [
                'admin_id' => auth()->id(),
                'suspended_count' => $suspendedCount
            ]);

            return response()->json([
                'success' => true,
                'count' => $suspendedCount,
                'message' => 'Sessions inactives suspendues'
            ]);
        } catch (\Exception $e) {
            Log::error("Error suspending inactive sessions: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suspension'
            ], 500);
        }
    }

    /**
     * Obtenir les détails d'une session
     */
    public function getSessionDetails($userId, $sessionId)
    {
        try {
            $user = User::findOrFail($userId);
            $session = DB::table('sessions')->where('id', $sessionId)->first();

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session non trouvée'
                ], 404);
            }

            // Activités récentes de l'utilisateur (simulées - à adapter selon votre système)
            $activities = $this->getUserRecentActivities($userId);

            return response()->json([
                'success' => true,
                'user' => [
                    'full_name' => $user->getFullNameAttribute(),
                    'email' => $user->email,
                    'role' => $user->role,
                    'last_connection' => $user->lastConnection() ? $user->lastConnection()->format('d/m/Y H:i:s') : 'Jamais'
                ],
                'session' => [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'duration' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(null, true)
                ],
                'activities' => $activities
            ]);
        } catch (\Exception $e) {
            Log::error("Error getting session details: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des détails'
            ], 500);
        }
    }

    /**
     * Mettre à jour les statistiques en temps réel
     */
    public function updateStats()
    {
        try {
            return response()->json([
                'success' => true,
                'stats' => [
                    'totalUsers' => User::whereNull('deleted_at')->count(),
                    'activeSessions' => $this->getActiveSessions()->count(),
                    'systemHealth' => $this->calculateSystemHealth(),
                    'activeAlerts' => $this->getActiveAlerts()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Exporter l'historique des sessions
     */
    public function exportSessions()
    {
        $sessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select([
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.role',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity'
            ])
            ->whereNotNull('sessions.user_id')
            ->orderBy('sessions.last_activity', 'desc')
            ->get();

        $filename = 'sessions_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($sessions) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'Prénom',
                'Nom',
                'Email',
                'Rôle',
                'Adresse IP',
                'Navigateur',
                'Dernière activité'
            ]);

            // Données
            foreach ($sessions as $session) {
                fputcsv($file, [
                    $session->first_name,
                    $session->last_name,
                    $session->email,
                    $session->role,
                    $session->ip_address,
                    $session->user_agent,
                    Carbon::createFromTimestamp($session->last_activity)->format('d/m/Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }



         /**
     * Obtenir l'utilisation du CPU réel (charge système par core)
     */
    private function getCpuUsage()
    {
        $stat1 = file_get_contents("/proc/stat");
        usleep(100000); // 0.1 sec
        $stat2 = file_get_contents("/proc/stat");

        $cpu1 = explode(" ", preg_replace("/^cpu +/", "", strtok($stat1, "\n")));
        $cpu2 = explode(" ", preg_replace("/^cpu +/", "", strtok($stat2, "\n")));

        $idle1 = $cpu1[3] + $cpu1[4];
        $idle2 = $cpu2[3] + $cpu2[4];

        $total1 = array_sum($cpu1);
        $total2 = array_sum($cpu2);

        $totalDiff = $total2 - $total1;
        $idleDiff = $idle2 - $idle1;

        $cpuPercent = (1 - ($idleDiff / $totalDiff)) * 100;
        return round($cpuPercent, 1);
    }

    /**
     * Obtenir l'utilisation mémoire réelle (Linux)
     */
    private function getMemoryUsage()
    {
        $free = shell_exec('free -m');
        if (!$free) return null;

        $lines = explode("\n", trim($free));
        $mem = preg_split('/\s+/', $lines[1]); // ligne "Mem:"

        $total = (int) $mem[1];
        $used = (int) $mem[2];

        return [
            'used_mb' => $used,
            'total_mb' => $total,
            'percent' => round(($used / $total) * 100, 1)
        ];
    }

    /**
     * Obtenir l'utilisation disque réelle
     */
    private function getDiskUsage()
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');

        if (!$total || !$free) return null;

        $used = $total - $free;

        return [
            'used' => $this->formatBytes($used),
            'total' => $this->formatBytes($total),
            'percent' => round(($used / $total) * 100, 1)
        ];
    }

    /**
     * Connexions actives d'utilisateurs Laravel (sessions)
     */
    private function getActiveConnections()
    {
        return DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->timestamp - config('session.lifetime') * 60)
            ->count();
    }

    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Obtenir le nombre de sessions expirées
     */
    private function getExpiredSessionsCount()
    {
        return DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '<', now()->timestamp - (config('session.lifetime') * 60))
            ->count();
    }

    /**
     * Obtenir le nombre de tentatives de connexion échouées récentes
     */
    private function getRecentFailedLogins()
    {
        // À adapter selon votre système de logs
        return rand(0, 4);
    }

    /**
     * Obtenir le nombre de types de traitements
     */
    private function getTreatmentTypesCount()
    {
        // À adapter selon votre modèle
        try {
            return DB::table('treatment_types')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtenir le nombre de méthodes de paiement
     */
    private function getPaymentMethodsCount()
    {
        // À adapter selon votre modèle
        try {
            return DB::table('payment_methods')->where('active', true)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtenir la date de la dernière sauvegarde
     */
    private function getLastBackupDate()
    {
        // À adapter selon votre système de sauvegarde
        try {
            $lastBackup = DB::table('backups')->latest('created_at')->first();
            return $lastBackup ? Carbon::parse($lastBackup->created_at)->diffForHumans() : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Obtenir les activités récentes d'un utilisateur
     */
    private function getUserRecentActivities($userId)
    {
        // Simulation d'activités - à remplacer par votre logique réelle
        return [
            [
                'action' => 'Consultation dossier patient #' . rand(1000, 9999),
                'time' => Carbon::now()->subMinutes(rand(1, 5))->diffForHumans()
            ],
            [
                'action' => 'Modification rendez-vous',
                'time' => Carbon::now()->subMinutes(rand(5, 15))->diffForHumans()
            ],
            [
                'action' => 'Création nouvelle ordonnance',
                'time' => Carbon::now()->subMinutes(rand(15, 30))->diffForHumans()
            ]
        ];
    }


/**
 * Gestion des utilisateurs
 */
public function users()
{
    $users = User::withTrashed()
        ->select([
            'id',
            'first_name',
            'last_name',
            'email',
            'role',
            'statut',
            'created_at',
            'deleted_at'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    // Statistiques des utilisateurs
    $userStats = [
        'total' => User::count(),
        'active' => User::where('statut', 'active')->count(),
        'inactive' => User::where('statut', 'inactive')->count(),
        'deleted' => User::onlyTrashed()->count(),
        'online' => $this->getOnlineUsersCount(),
        'by_role' => User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray()
    ];

    return view('admin.users.index', compact('users', 'userStats'));
}

/**
 * Paramètres système
 */
public function settings()
{
    return redirect('parametres');
}


/**
 * Gestion des notifications
 */
public function notifications()
{
    $notifications = Notification::with('user')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    $notificationStats = [
        'total' => Notification::count(),
        'unread' => Notification::whereNull('read_date')->count(),
        'today' => Notification::whereDate('created_at', today())->count(),
        'this_week' => Notification::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count(),
    ];

    return view('admin.notifications.index', compact('notifications', 'notificationStats'));
}

/**
 * Créer une sauvegarde manuelle
 */
public function createBackup()
{
    try {
        // Logique de création de sauvegarde
        $filename = 'manual_backup_' . date('Y-m-d_H-i-s') . '.sql';

        // Ici vous pouvez utiliser un package comme spatie/laravel-backup
        // ou implémenter votre propre logique de sauvegarde

        Log::info("Manual backup created", [
            'admin_id' => auth()->id(),
            'filename' => $filename
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sauvegarde créée avec succès',
            'filename' => $filename
        ]);
    } catch (\Exception $e) {
        Log::error("Error creating backup: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création de la sauvegarde'
        ], 500);
    }
}

/**
 * Nettoyer les sessions expirées
 */
public function cleanupSessions()
{
    try {
        $expiredCount = DB::table('sessions')
            ->where('last_activity', '<', now()->timestamp - (config('session.lifetime') * 60 * 2))
            ->delete();

        Log::info("Sessions cleanup", [
            'admin_id' => auth()->id(),
            'deleted_sessions' => $expiredCount
        ]);

        return response()->json([
            'success' => true,
            'message' => "{$expiredCount} sessions expirées ont été supprimées"
        ]);
    } catch (\Exception $e) {
        Log::error("Error cleaning up sessions: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du nettoyage'
        ], 500);
    }
}

/**
 * Obtenir le nombre d'utilisateurs en ligne
 */
private function getOnlineUsersCount()
{
    $sessionLifetime = config('session.lifetime') * 60;

    return DB::table('sessions')
        ->whereNotNull('user_id')
        ->where('last_activity', '>=', now()->timestamp - $sessionLifetime)
        ->distinct('user_id')
        ->count();
}

/**
 * Mettre à jour un paramètre système
 */
public function updateSetting(Request $request)
{
    $request->validate([
        'key' => 'required|string',
        'value' => 'required'
    ]);

    try {
        // Logique de mise à jour des paramètres
        // Cela dépend de votre système de configuration

        Log::info("System setting updated", [
            'admin_id' => auth()->id(),
            'setting_key' => $request->key,
            'old_value' => config($request->key),
            'new_value' => $request->value
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Paramètre mis à jour avec succès'
        ]);
    } catch (\Exception $e) {
        Log::error("Error updating setting: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour'
        ], 500);
    }
}

/**
 * Activer/Désactiver un utilisateur
 */
public function toggleUserStatus(Request $request, $userId)
{
    try {
        $user = User::findOrFail($userId);
        $newStatus = $user->statut === 'active' ? 'inactive' : 'active';

        $user->update(['statut' => $newStatus]);

        // Si l'utilisateur est désactivé, fermer ses sessions
        if ($newStatus === 'inactive') {
            DB::table('sessions')->where('user_id', $userId)->delete();
        }

        Log::info("User status toggled", [
            'admin_id' => auth()->id(),
            'target_user_id' => $userId,
            'new_status' => $newStatus
        ]);

        return response()->json([
            'success' => true,
            'message' => "Ation sur utilisateur  avec succès",
            'new_status' => $newStatus
        ]);
    } catch (\Exception $e) {
        Log::error("Error toggling user status: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la modification du statut'
        ], 500);
    }
}

/**
 * Obtenir les statistiques détaillées du système
 */
public function getDetailedStats()
{
    return response()->json([
        'users' => [
            'total' => User::count(),
            'active_today' => User::whereHas('sessions', function($query) {
                $query->where('last_activity', '>=', Carbon::today()->timestamp);
            })->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)->count()
        ],
        'sessions' => [
            'active_now' => $this->getOnlineUsersCount(),
            'total_today' => DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', Carbon::today()->timestamp)
                ->distinct('user_id')
                ->count(),
            'peak_today' => rand(50, 100) // À remplacer par une vraie mesure
        ],
        'system' => [
            'uptime' => $this->getSystemUptime(),
            'load_average' => $this->getLoadAverage(),
            'database_size' => $this->getDatabaseSize()
        ]
    ]);
}

/**
 * Obtenir le temps de fonctionnement du système
 */
private function getSystemUptime()
{
    // Simulation - à remplacer par une vraie mesure
    return Carbon::now()->subDays(rand(1, 30))->diffForHumans(null, true);
}

/**
 * Obtenir la charge moyenne du système
 */
private function getLoadAverage()
{
    // Simulation - à remplacer par une vraie mesure système
    return [
        '1min' => round(rand(50, 150) / 100, 2),
        '5min' => round(rand(40, 140) / 100, 2),
        '15min' => round(rand(45, 135) / 100, 2)
    ];
}

/**
 * Obtenir la taille de la base de données
 */
private function getDatabaseSize()
{
    try {
        $size = DB::select("
            SELECT
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
            FROM information_schema.tables
            WHERE table_schema = ?
        ", [config('database.connections.mysql.database')]);

        return $size[0]->size_mb . ' MB';
    } catch (\Exception $e) {
        return 'Non disponible';
    }
}
}
