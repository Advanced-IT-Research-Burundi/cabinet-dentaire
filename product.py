-- Orthodontie
INSERT INTO `stocks`(
  `product_name`, `description`, `available_quantity`, `unit_measure`,
  `minimum_quantity`, `purchase_price`, `price`, `supplier`, `category_id`,
  `created_at`, `updated_at`
) VALUES
('Ligature ties (elastics)', 'Élastiques orthodontiques', 200, 'boîte', 50, 8.00, 15.00, 'Gurber', 1, NOW(), NOW()),
('Interdental Brush 8p', 'Brossettes interdentaires 8p', 150, 'pack', 30, 4.50, 9.00, 'Ortho Supplier', 1, NOW(), NOW()),
('Interdental brush 1p', 'Brossette interdentaire individuelle', 300, 'pièce', 100, 0.80, 1.50, 'Ortho Supplier', 1, NOW(), NOW()),
('Brackets Metalic (Gurber)', 'Brackets métalliques', 500, 'pack', 100, 20.00, 40.00, 'Gurber', 1, NOW(), NOW()),
('Brackets Ceramic (Gurber)', 'Brackets céramiques', 300, 'pack', 50, 30.00, 60.00, 'Gurber', 1, NOW(), NOW()),
('Ortho Rubber band (Gurber)', 'Élastiques orthodontiques', 400, 'boîte', 100, 12.00, 25.00, 'Gurber', 1, NOW(), NOW()),
('Archwire NiTi', 'Arc orthodontique NiTi', 100, 'unité', 30, 25.00, 50.00, 'Ortho Supplier', 1, NOW(), NOW()),
('Ortho toothbrush', 'Brosse à dents orthodontique', 200, 'unité', 50, 3.00, 6.00, 'Oral-B', 1, NOW(), NOW()),
('Ligature wire', 'Fil de ligature', 150, 'rouleau', 40, 15.00, 30.00, 'Ortho Supplier', 1, NOW(), NOW()),
('Wire cutter/bracket remover', 'Pince à brackets', 50, 'unité', 10, 35.00, 70.00, 'Dental Instruments', 1, NOW(), NOW());

-- Produits Dengo India
INSERT INTO `stocks`(
  `product_name`, `description`, `available_quantity`, `unit_measure`,
  `minimum_quantity`, `purchase_price`, `price`, `supplier`, `category_id`,
  `created_at`, `updated_at`
) VALUES
('Dengotemp Temp Filling 40 gm', 'Ciment temporaire', 80, 'tube', 20, 10.00, 20.00, 'Dengo India', 2, NOW(), NOW()),
('Dengocem 1 Lutting cement 15gm/10ml', 'Ciment de scellement', 60, 'kit', 15, 15.00, 30.00, 'Dengo India', 2, NOW(), NOW()),
('ZPCO Zinc Polycarboxylate cement 15ml', 'Ciment polycarboxylate', 45, 'flacon', 10, 12.00, 25.00, 'Dengo India', 2, NOW(), NOW()),
('Dentogyl Dry socket paste 12gm', 'Pâte pour alvéolite', 70, 'tube', 15, 8.00, 16.00, 'Dengo India', 2, NOW(), NOW()),
('Dengocal Calcium Hydroxyde paste 3gm', 'Pâte à l\'hydroxyde de calcium', 90, 'tube', 25, 7.00, 14.00, 'Dengo India', 2, NOW(), NOW()),
('Dengoglass light cure GIC 4*2gm', 'Ciment verre ionomère', 40, 'kit', 10, 35.00, 70.00, 'Dengo India', 2, NOW(), NOW()),
('Hemostat gel hemostatic agent 15ml', 'Gel hémostatique', 55, 'tube', 15, 18.00, 36.00, 'Dengo India', 2, NOW(), NOW());

-- Produits Guccident Foshan (Instruments et consommables)
INSERT INTO `stocks`(
  `product_name`, `description`, `available_quantity`, `unit_measure`,
  `minimum_quantity`, `purchase_price`, `price`, `supplier`, `category_id`,
  `created_at`, `updated_at`
) VALUES
('Algenate Impression', 'Pâte à empreinte alginate', 30, 'boîte', 10, 25.00, 50.00, 'Guccident Foshan', 3, NOW(), NOW()),
('Dental Bur (Carbide) 1 pc', 'Fraise carbure', 500, 'pièce', 150, 3.00, 6.00, 'Guccident Foshan', 3, NOW(), NOW()),
('Gutta Percha', 'Gutta percha', 200, 'boîte', 50, 12.00, 25.00, 'Guccident Foshan', 3, NOW(), NOW()),
('Dental Mixing bowl', 'Bol à mélanger', 40, 'unité', 10, 5.00, 10.00, 'Guccident Foshan', 3, NOW(), NOW()),
('High Speed handpiece', 'Contre-angle', 15, 'unité', 3, 150.00, 300.00, 'Guccident Foshan', 3, NOW(), NOW()),
('Curing Light LED', 'Lampe polymérisation LED', 10, 'unité', 2, 120.00, 250.00, 'Guccident Foshan', 3, NOW(), NOW()),
('Amalgam Capsule', 'Capsule pour amalgame', 200, 'boîte', 50, 20.00, 40.00, 'Guccident Foshan', 3, NOW(), NOW());

-- Brosses à dents
INSERT INTO `stocks`(
  `product_name`, `description`, `available_quantity`, `unit_measure`,
  `minimum_quantity`, `purchase_price`, `price`, `supplier`, `category_id`,
  `created_at`, `updated_at`
) VALUES
('Water Flosser', 'Hydropulseur', 25, 'unité', 5, 60.00, 120.00, 'Oral-B', 4, NOW(), NOW()),
('Electric tooth brush', 'Brosse à dents électrique', 30, 'unité', 10, 45.00, 90.00, 'Philips', 4, NOW(), NOW()),
('U shaped baby brush', 'Brosse bébé forme U', 50, 'unité', 15, 4.00, 8.00, 'Kids Dental', 4, NOW(), NOW()),
('Thumb Silicone Baby brush', 'Brosse doigt silicone bébé', 60, 'unité', 20, 3.50, 7.00, 'Kids Dental', 4, NOW(), NOW());

-- Dentifrices
INSERT INTO `stocks`(
  `product_name`, `description`, `available_quantity`, `unit_measure`,
  `minimum_quantity`, `purchase_price`, `price`, `supplier`, `category_id`,
  `created_at`, `updated_at`
) VALUES
('COLGATE TOTAL 7-12 YEARS MILD MINT', 'Dentifrice enfants 7-12 ans', 100, 'tube', 30, 2.50, 5.00, 'Colgate', 5, NOW(), NOW()),
('SENSODYNE COMPLETE PROTECTION', 'Dentifrice dents sensibles', 80, 'tube', 25, 3.50, 7.00, 'Sensodyne', 5, NOW(), NOW()),
('ORAL B 3D WHITE THERAPY WHITENING', 'Dentifrice blancheur', 70, 'tube', 20, 4.00, 8.00, 'Oral-B', 5, NOW(), NOW()),
('COLGATE CHARCOAL ADVANCED WHITENING', 'Dentifrice charbon actif', 60, 'tube', 15, 3.80, 7.50, 'Colgate', 5, NOW(), NOW()),
('AQUAFRESH MILK TEETH BABY 0-2YRS', 'Dentifrice bébé 0-2 ans', 90, 'tube', 25, 3.00, 6.00, 'Aquafresh', 5, NOW(), NOW());
