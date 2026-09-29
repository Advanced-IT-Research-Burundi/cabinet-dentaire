<?php

namespace Tests\Feature;

use App\Models\Caisse;
use App\Models\CaisseDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CaisseCentraleTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake();
        $this->admin = User::factory()->create(['role' => 'Admin']);
        $this->actingAs($this->admin);
    }

    private function caisseUtilisateur(float $montant): Caisse
    {
        return Caisse::create([
            'type' => 'income',
            'date' => now(),
            'montant' => $montant,
            'status' => 'active',
            'user_id' => User::factory()->create(['role' => 'Secretaire'])->id,
        ]);
    }

    public function test_index_affiche_la_caisse_centrale(): void
    {
        $this->get(route('caisse-centrale.index'))->assertOk()->assertSee('Caisse Centrale');
        $this->assertSame(1, Caisse::where('is_centrale', true)->count());
    }

    public function test_liste_des_caisses_exclut_la_centrale(): void
    {
        $caisse = $this->caisseUtilisateur(2500);

        $this->get(route('caisses.index'))->assertOk()->assertSee('Caisse Centrale');
        $this->get(route('caisses.show', $caisse))->assertOk();
        $this->get(route('caisses.show', Caisse::centrale()))->assertRedirect(route('caisse-centrale.index'));
    }

    public function test_non_admin_ne_peut_pas_acceder(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'Secretaire']));

        $this->get(route('caisse-centrale.index'))->assertForbidden();
    }

    public function test_collecte_diminue_la_caisse_utilisateur_et_credite_la_centrale(): void
    {
        $caisse = $this->caisseUtilisateur(10000);
        $soldeCentrale = Caisse::centrale()->montant;

        $this->patch(route('caisse-centrale.collecter', $caisse), [
            'montant_retrait' => 4000,
            'motif_retrait' => 'Versement fin de journée',
            'justificatif' => UploadedFile::fake()->create('recu.pdf', 100, 'application/pdf'),
        ])->assertSessionHasNoErrors();

        $this->assertEquals(6000, $caisse->fresh()->montant);
        $this->assertEquals($soldeCentrale + 4000, Caisse::centrale()->montant);

        $ligne = CaisseDetail::where('caisse_id', Caisse::centrale()->id)->latest('id')->first();
        $this->assertSame(COLLECTE_CAISSE, $ligne->operation_type);
        $this->assertEquals(4000, $ligne->total);
        Storage::assertExists($ligne->justificatif);

        $this->assertEquals(-4000, CaisseDetail::where('caisse_id', $caisse->id)->latest('id')->first()->total);
    }

    public function test_collecte_refusee_si_montant_superieur_au_solde(): void
    {
        $caisse = $this->caisseUtilisateur(1000);

        $this->patch(route('caisse-centrale.collecter', $caisse), [
            'montant_retrait' => 5000,
            'motif_retrait' => 'Trop gros montant',
        ])->assertSessionHasErrors('montant_retrait');

        $this->assertEquals(1000, $caisse->fresh()->montant);
    }

    public function test_operations_bancaires_entree_et_sortie(): void
    {
        $centrale = Caisse::centrale();
        $centrale->update(['montant' => 0]);

        $this->post(route('caisse-centrale.operations.store'), [
            'operation_type' => 'RETRAIT_BANQUE',
            'montant' => 50000,
            'date_operation' => now()->toDateString(),
            'banque' => 'BANCOBU',
            'reference' => 'CHQ-001',
            'justificatif' => UploadedFile::fake()->image('bordereau.jpg'),
        ])->assertSessionHasNoErrors();

        $this->post(route('caisse-centrale.operations.store'), [
            'operation_type' => 'VERSEMENT_BANQUE',
            'montant' => 20000,
            'date_operation' => now()->toDateString(),
        ])->assertSessionHasNoErrors();

        $this->assertEquals(30000, $centrale->fresh()->montant);

        // Sortie supérieure au solde : refusée, fichier non conservé
        $this->post(route('caisse-centrale.operations.store'), [
            'operation_type' => 'FRAIS_BANCAIRES',
            'montant' => 999999,
            'date_operation' => now()->toDateString(),
            'justificatif' => UploadedFile::fake()->image('frais.jpg'),
        ])->assertSessionHasErrors('montant');

        $this->assertEquals(30000, $centrale->fresh()->montant);
        $this->assertCount(1, Storage::allFiles('justificatifs/caisse-centrale'));
    }

    public function test_ajout_justificatif_apres_coup(): void
    {
        $this->post(route('caisse-centrale.operations.store'), [
            'operation_type' => 'AUTRE_ENTREE',
            'montant' => 1000,
            'date_operation' => now()->toDateString(),
        ]);
        $ligne = CaisseDetail::latest('id')->first();

        $this->post(route('caisse-centrale.justificatif.store', $ligne), [
            'justificatif' => UploadedFile::fake()->create('piece.pdf', 50, 'application/pdf'),
        ])->assertSessionHasNoErrors();

        $this->assertNotNull($ligne->fresh()->justificatif);
        $this->get(route('caisse-centrale.justificatif.show', $ligne))->assertOk();
    }
}
