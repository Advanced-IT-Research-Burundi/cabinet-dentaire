from datetime import datetime

# Données fournies
donnees = [
    ("Consultation", 15000, None),
    ("Prendre et interpréter la radio intra orale", 30000, None),
    ("Prendre et interpréter la radio panoramique", 60000, None),
    ("Détartrage et nettoyage  pédiatrique", 50000, 80000),
    ("Détartrage et nettoyage (adulte)", 80000, 120000),
    ("Traitement gingival (traitement protocolaire de parodontie et gingivite)", 80000, 200000),
    ("Traitement au fluorure", 20000, None),
    ("Obturation provisoire", 25000, 40000),
    ("Plombage par amalgame, une face", 60000, None),
    ("Plombage par amalgame double face", 80000, None),
    ("Plombage par amalgame, multi face", 120000, None),
    ("Plombage par composite, simple face", 80000, None),
    ("Plombage par composite, double face", 120000, None),
    ("Plombage par composite, multi face", 130000, 150000),
    ("Obturation a l’ionomère de verre", 90000, 120000),
    ("Obturation a l’ionomère de verre multi faces", 150000, 200000),
    ("Restauration de la couronne anatomique fracturée", 120000, None),
    ("Coiffage pulpaire", 60000, None),
    ("Traitement canalaire (radiographie, anesthésie, dévitalisation pulpaire, extirpation pulpaire, traitement radiculaire restauration définitive)", 240000, 600000),
    ("Extraction d’une dent de lait mobile", 30000, None),
    ("Extraction d’une dent de lait infectée", 40000, 50000),
    ("Extraction d’une dent de lait sous anesthésie injectable", 40000, None),
    ("Extraction d’une dent de lait fracturée", 50000, None),
    ("Extraction simple pour adulte", 60000, None),
    ("Extraction chirurgicale", 100000, 200000),
    ("Extraction d’une dent de sagesse incluse", 300000, 800000),
    ("Extraction d’une dent fracturée (chirurgicale)", 100000, 200000),
    ("Extraction d’une dent de sagesse maxillaire", 150000, 300000),
    ("Extraction d’une dent de sagesse mandibulaire", 250000, 350000),
    ("Extraction d’une dent éctopique", 80000, 150000),
    ("Extraction d’un chicot (Restes radiculaires)", 50000, 200000),
    ("Curetage alvéolaire", 50000, None),
    ("Incision d’un abcès", 50000, None),
    ("Sutures", 15000, 50000),
    ("Ablation des sutures", 10000, None),
    ("Prothèse amovible d’une dent", 180000, 200000),
    ("Prothèse amovible d’une dent (express)", 250000, 300000),
    ("Prothèse fixe en céramique d’une dent", 300000, None),
    ("Prothèse fixe en porcelaine", 560000, None),
    ("Prothèse fixe (zirconium)", 650000, None),
    ("Demontage+montage d’une couronne", 60000, None),
    ("Demontage+montage d’une prothèse fixe", 40000, 50000),
    ("Gingivectomie", 50000, 300000),
    ("Soins hémostatiques", 10000, 50000),
    ("Traitement endodontique", 10000, 50000),
    ("Soins dentaires", 10000, 50000),
    ("Traitement orthodontique/mâchoire", 1500000, 2000000),
    ("Ajustement de l’appareil orthodontique", 20000, 1000000),
    ("Bague perdue (traitement orthodontique)", 15000, 30000),
    ("Remplacement du fil (traitement orthodontique)", 15000, 60000),
    ("Retainer", 200000, 300000),
    ("Fixation d’une dislocation  temporo- mandibulaire", 150000, 300000),
    ("Correction d’une malocclusion dentaire/mâchoire", 1500000, 2000000),
    ("Expansion mandibulaire par vérin", 570000, None),
    ("Expansion maxillaire par vérin", 570000, None)
]

# Générer les requêtes
now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
requetes = []

requetes.append("SET FOREIGN_KEY_CHECKS=0;")
requetes.append("truncate `treatment_types`;")
for i, (name, base_price, max_price) in enumerate(donnees, 1):
    requete = f"""INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES ({i}, '{name.replace("'", "''")}', NULL, 30, {base_price}, NULL, {i}, 1, {max_price if max_price is not None else 'NULL'}, '{now}', '{now}');"""
    requetes.append(requete)
    
requetes.append("SET FOREIGN_KEY_CHECKS=1;")
requetes.append("COMMIT;")
# Sauvegarder dans un fichier SQL
sql_script = "\n".join(requetes)
file_path = "insert_treatment_types.sql"
# Support UTF-8 encoding for special characters
with open(file_path, "w", encoding="utf-8") as f:
    f.write(sql_script)
