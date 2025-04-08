SET FOREIGN_KEY_CHECKS=0;
truncate `treatment_types`;
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (1, 'Consultation', NULL, 30, 15000, NULL, 1, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (2, 'Prendre et interpréter la radio intra orale', NULL, 30, 30000, NULL, 2, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (3, 'Prendre et interpréter la radio panoramique', NULL, 30, 60000, NULL, 3, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (4, 'Détartrage et nettoyage  pédiatrique', NULL, 30, 50000, NULL, 4, 1, 80000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (5, 'Détartrage et nettoyage (adulte)', NULL, 30, 80000, NULL, 5, 1, 120000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (6, 'Traitement gingival (traitement protocolaire de parodontie et gingivite)', NULL, 30, 80000, NULL, 6, 1, 200000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (7, 'Traitement au fluorure', NULL, 30, 20000, NULL, 7, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (8, 'Obturation provisoire', NULL, 30, 25000, NULL, 8, 1, 40000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (9, 'Plombage par amalgame, une face', NULL, 30, 60000, NULL, 9, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (10, 'Plombage par amalgame double face', NULL, 30, 80000, NULL, 10, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (11, 'Plombage par amalgame, multi face', NULL, 30, 120000, NULL, 11, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (12, 'Plombage par composite, simple face', NULL, 30, 80000, NULL, 12, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (13, 'Plombage par composite, double face', NULL, 30, 120000, NULL, 13, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (14, 'Plombage par composite, multi face', NULL, 30, 130000, NULL, 14, 1, 150000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (15, 'Obturation a l’ionomère de verre', NULL, 30, 90000, NULL, 15, 1, 120000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (16, 'Obturation a l’ionomère de verre multi faces', NULL, 30, 150000, NULL, 16, 1, 200000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (17, 'Restauration de la couronne anatomique fracturée', NULL, 30, 120000, NULL, 17, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (18, 'Coiffage pulpaire', NULL, 30, 60000, NULL, 18, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (19, 'Traitement canalaire (radiographie, anesthésie, dévitalisation pulpaire, extirpation pulpaire, traitement radiculaire restauration définitive)', NULL, 30, 240000, NULL, 19, 1, 600000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (20, 'Extraction d’une dent de lait mobile', NULL, 30, 30000, NULL, 20, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (21, 'Extraction d’une dent de lait infectée', NULL, 30, 40000, NULL, 21, 1, 50000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (22, 'Extraction d’une dent de lait sous anesthésie injectable', NULL, 30, 40000, NULL, 22, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (23, 'Extraction d’une dent de lait fracturée', NULL, 30, 50000, NULL, 23, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (24, 'Extraction simple pour adulte', NULL, 30, 60000, NULL, 24, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (25, 'Extraction chirurgicale', NULL, 30, 100000, NULL, 25, 1, 200000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (26, 'Extraction d’une dent de sagesse incluse', NULL, 30, 300000, NULL, 26, 1, 800000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (27, 'Extraction d’une dent fracturée (chirurgicale)', NULL, 30, 100000, NULL, 27, 1, 200000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (28, 'Extraction d’une dent de sagesse maxillaire', NULL, 30, 150000, NULL, 28, 1, 300000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (29, 'Extraction d’une dent de sagesse mandibulaire', NULL, 30, 250000, NULL, 29, 1, 350000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (30, 'Extraction d’une dent éctopique', NULL, 30, 80000, NULL, 30, 1, 150000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (31, 'Extraction d’un chicot (Restes radiculaires)', NULL, 30, 50000, NULL, 31, 1, 200000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (32, 'Curetage alvéolaire', NULL, 30, 50000, NULL, 32, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (33, 'Incision d’un abcès', NULL, 30, 50000, NULL, 33, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (34, 'Sutures', NULL, 30, 15000, NULL, 34, 1, 50000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (35, 'Ablation des sutures', NULL, 30, 10000, NULL, 35, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (36, 'Prothèse amovible d’une dent', NULL, 30, 180000, NULL, 36, 1, 200000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (37, 'Prothèse amovible d’une dent (express)', NULL, 30, 250000, NULL, 37, 1, 300000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (38, 'Prothèse fixe en céramique d’une dent', NULL, 30, 300000, NULL, 38, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (39, 'Prothèse fixe en porcelaine', NULL, 30, 560000, NULL, 39, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (40, 'Prothèse fixe (zirconium)', NULL, 30, 650000, NULL, 40, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (41, 'Demontage+montage d’une couronne', NULL, 30, 60000, NULL, 41, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (42, 'Demontage+montage d’une prothèse fixe', NULL, 30, 40000, NULL, 42, 1, 50000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (43, 'Gingivectomie', NULL, 30, 50000, NULL, 43, 1, 300000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (44, 'Soins hémostatiques', NULL, 30, 10000, NULL, 44, 1, 50000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (45, 'Traitement endodontique', NULL, 30, 10000, NULL, 45, 1, 50000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (46, 'Soins dentaires', NULL, 30, 10000, NULL, 46, 1, 50000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (47, 'Traitement orthodontique/mâchoire', NULL, 30, 1500000, NULL, 47, 1, 2000000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (48, 'Ajustement de l’appareil orthodontique', NULL, 30, 20000, NULL, 48, 1, 1000000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (49, 'Bague perdue (traitement orthodontique)', NULL, 30, 15000, NULL, 49, 1, 30000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (50, 'Remplacement du fil (traitement orthodontique)', NULL, 30, 15000, NULL, 50, 1, 60000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (51, 'Retainer', NULL, 30, 200000, NULL, 51, 1, 300000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (52, 'Fixation d’une dislocation  temporo- mandibulaire', NULL, 30, 150000, NULL, 52, 1, 300000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (53, 'Correction d’une malocclusion dentaire/mâchoire', NULL, 30, 1500000, NULL, 53, 1, 2000000, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (54, 'Expansion mandibulaire par vérin', NULL, 30, 570000, NULL, 54, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
INSERT INTO `treatment_types`(`id`, `name`, `description`, `average_duration`, `base_price`, `category`, `code`, `active`, `max_price`, `created_at`, `updated_at`) 
VALUES (55, 'Expansion maxillaire par vérin', NULL, 30, 570000, NULL, 55, 1, NULL, '2025-04-08 06:58:27', '2025-04-08 06:58:27');
SET FOREIGN_KEY_CHECKS=1;
COMMIT;