CREATE TYPE role_enum AS ENUM ('GESTIONNAIRE', 'CLIENT', 'LIVREUR');

CREATE TYPE type_complement_enum AS ENUM ('FRITES', 'BOISSON');

CREATE TYPE etat_commande_enum AS ENUM (
'EN_ATTENTE',
'CONFIRMEE',
'EN_PREPARATION',
'PRETE',
'LIVREE',
'RETIREE',
'CONSOMMEE_SUR_PLACE',
'ANNULEE'
);

CREATE TYPE type_livraison_enum AS ENUM (
'SUR_PLACE',
'A_RECUPERER',
'A_LIVRER'
);

CREATE TYPE type_article_enum AS ENUM (
'BURGER',
'MENU',
'COMPLEMENT'
);

CREATE TYPE methode_paiement_enum AS ENUM (
'WAVE',
'ORANGE_MONEY'
);

CREATE TYPE statut_paiement_enum AS ENUM (
'EN_ATTENTE',
'REUSSI',
'ECHEC'
);

CREATE TABLE burger (
id SERIAL PRIMARY KEY,
nom VARCHAR(100) NOT NULL,
prix DECIMAL(10, 2) NOT NULL CHECK (prix >= 0),
description TEXT,
image VARCHAR(255),
is_archive BOOLEAN DEFAULT FALSE,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE complement (
id SERIAL PRIMARY KEY,
nom VARCHAR(100) NOT NULL,
type_complement type_complement_enum NOT NULL,
description TEXT,
image VARCHAR(255),
prix DECIMAL(10, 2) NOT NULL CHECK (prix >= 0),
is_archive BOOLEAN DEFAULT FALSE,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE zone (
id SERIAL PRIMARY KEY,
nom VARCHAR(100) NOT NULL,
quartiers TEXT[],
prix_livraison DECIMAL(10, 2) NOT NULL CHECK (prix_livraison >= 0),
is_archive BOOLEAN DEFAULT FALSE,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "user" (
id SERIAL PRIMARY KEY,
nom VARCHAR(100) NOT NULL,
prenom VARCHAR(100) NOT NULL,
email VARCHAR(150) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
adresse TEXT,
telephone VARCHAR(20) UNIQUE,
role role_enum NOT NULL DEFAULT 'CLIENT',
is_archive BOOLEAN DEFAULT FALSE,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE menu (
id SERIAL PRIMARY KEY,
nom VARCHAR(100) NOT NULL,
prix DECIMAL(10, 2) NOT NULL CHECK (prix >= 0),
description TEXT,
image VARCHAR(255),
id_burger INTEGER NOT NULL,
id_boisson INTEGER NOT NULL,
id_frite INTEGER NOT NULL,
is_archive BOOLEAN DEFAULT FALSE,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT fk_menu_burger FOREIGN KEY (id_burger) REFERENCES burger(id) ON DELETE RESTRICT,
CONSTRAINT fk_menu_boisson FOREIGN KEY (id_boisson) REFERENCES complement(id) ON DELETE RESTRICT,
CONSTRAINT fk_menu_frite FOREIGN KEY (id_frite) REFERENCES complement(id) ON DELETE RESTRICT
);

CREATE TABLE commande (
id SERIAL PRIMARY KEY,
id_client INTEGER NOT NULL,
date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
montant_total DECIMAL(10,2) NOT NULL CHECK (montant_total >= 0),
etat_commande etat_commande_enum DEFAULT 'EN_ATTENTE',
type_livraison type_livraison_enum NOT NULL,
adresse_livraison TEXT,
id_zone INTEGER,
id_livreur INTEGER,
CONSTRAINT fk_commande_client FOREIGN KEY (id_client) REFERENCES "user"(id) ON DELETE RESTRICT,
CONSTRAINT fk_commande_zone FOREIGN KEY (id_zone) REFERENCES zone(id) ON DELETE SET NULL,
CONSTRAINT fk_commande_livreur FOREIGN KEY (id_livreur) REFERENCES "user"(id) ON DELETE SET NULL,
CONSTRAINT chk_livraison_zone CHECK (
(type_livraison = 'A_LIVRER' AND id_zone IS NOT NULL)
OR (type_livraison IN ('SUR_PLACE', 'A_RECUPERER'))
)
);

CREATE TABLE detail_commande (
id SERIAL PRIMARY KEY,
id_commande INTEGER NOT NULL,
type_article type_article_enum NOT NULL,
id_article INTEGER NOT NULL,
quantite INTEGER NOT NULL DEFAULT 1 CHECK (quantite > 0),
prix_unitaire DECIMAL(10,2) NOT NULL CHECK (prix_unitaire >= 0),
sous_total DECIMAL(10,2) NOT NULL CHECK (sous_total >= 0),
CONSTRAINT fk_detail_commande FOREIGN KEY (id_commande) REFERENCES commande(id) ON DELETE CASCADE
);

CREATE TABLE paiement (
id SERIAL PRIMARY KEY,
id_commande INTEGER NOT NULL UNIQUE,
date_paiement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
montant DECIMAL(10,2) NOT NULL CHECK (montant >= 0),
methode_paiement methode_paiement_enum NOT NULL,
statut_paiement statut_paiement_enum DEFAULT 'EN_ATTENTE',
reference_transaction VARCHAR(100),
CONSTRAINT fk_paiement_commande FOREIGN KEY (id_commande) REFERENCES commande(id) ON DELETE RESTRICT
);

CREATE INDEX idx_burger_nom ON burger(nom);
CREATE INDEX idx_burger_is_archive ON burger(is_archive);

CREATE INDEX idx_complement_nom ON complement(nom);
CREATE INDEX idx_complement_type ON complement(type_complement);
CREATE INDEX idx_complement_is_archive ON complement(is_archive);

CREATE INDEX idx_zone_nom ON zone(nom);
CREATE INDEX idx_zone_is_archive ON zone(is_archive);

CREATE INDEX idx_user_email ON "user"(email);
CREATE INDEX idx_user_role ON "user"(role);
CREATE INDEX idx_user_is_archive ON "user"(is_archive);

CREATE INDEX idx_menu_nom ON menu(nom);
CREATE INDEX idx_menu_is_archive ON menu(is_archive);
CREATE INDEX idx_menu_burger ON menu(id_burger);
CREATE INDEX idx_menu_boisson ON menu(id_boisson);
CREATE INDEX idx_menu_frite ON menu(id_frite);

CREATE INDEX idx_commande_client ON commande(id_client);
CREATE INDEX idx_commande_date ON commande(date_commande);
CREATE INDEX idx_commande_etat ON commande(etat_commande);
CREATE INDEX idx_commande_type ON commande(type_livraison);
CREATE INDEX idx_commande_zone ON commande(id_zone);
CREATE INDEX idx_commande_livreur ON commande(id_livreur);

CREATE INDEX idx_detail_commande ON detail_commande(id_commande);
CREATE INDEX idx_detail_type ON detail_commande(type_article);
CREATE INDEX idx_detail_article ON detail_commande(type_article, id_article);

CREATE INDEX idx_paiement_commande ON paiement(id_commande);
CREATE INDEX idx_paiement_date ON paiement(date_paiement);
CREATE INDEX idx_paiement_statut ON paiement(statut_paiement);
CREATE INDEX idx_paiement_methode ON paiement(methode_paiement);

CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
NEW.updated_at = CURRENT_TIMESTAMP;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_burger_updated_at BEFORE UPDATE ON burger FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_complement_updated_at BEFORE UPDATE ON complement FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_zone_updated_at BEFORE UPDATE ON zone FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_user_updated_at BEFORE UPDATE ON "user" FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_menu_updated_at BEFORE UPDATE ON menu FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE VIEW commandes_jour AS
SELECT c.*, u.nom || ' ' || u.prenom AS client_nom, z.nom AS zone_nom, l.nom || ' ' || l.prenom AS livreur_nom
FROM commande c
JOIN "user" u ON c.id_client = u.id
LEFT JOIN zone z ON c.id_zone = z.id
LEFT JOIN "user" l ON c.id_livreur = l.id
WHERE DATE(c.date_commande) = CURRENT_DATE;

CREATE VIEW stats_journalieres AS
SELECT COUNT(*) FILTER (WHERE etat_commande IN ('EN_ATTENTE', 'CONFIRMEE', 'EN_PREPARATION', 'PRETE')) AS commandes_en_cours,
COUNT(*) FILTER (WHERE etat_commande IN ('LIVREE', 'RETIREE', 'CONSOMMEE_SUR_PLACE')) AS commandes_validees,
COUNT(*) FILTER (WHERE etat_commande = 'ANNULEE') AS commandes_annulees,
COALESCE(SUM(montant_total) FILTER (WHERE etat_commande IN ('LIVREE', 'RETIREE', 'CONSOMMEE_SUR_PLACE')), 0) AS recettes
FROM commande
WHERE DATE(date_commande) = CURRENT_DATE;

CREATE VIEW commandes_details AS
SELECT c.id AS commande_id, c.date_commande, c.montant_total, c.etat_commande, c.type_livraison,
u.nom || ' ' || u.prenom AS client, u.telephone AS client_telephone,
z.nom AS zone, l.nom || ' ' || l.prenom AS livreur,
p.statut_paiement, p.methode_paiement
FROM commande c
JOIN "user" u ON c.id_client = u.id
LEFT JOIN zone z ON c.id_zone = z.id
LEFT JOIN "user" l ON c.id_livreur = l.id
LEFT JOIN paiement p ON c.id = p.id_commande;

CREATE OR REPLACE FUNCTION calculer_prix_menu(p_menu_id INTEGER)
RETURNS DECIMAL(10,2) AS $$
DECLARE v_prix DECIMAL(10,2);
BEGIN
SELECT b.prix + bo.prix + f.prix INTO v_prix
FROM menu m
JOIN burger b ON m.id_burger = b.id
JOIN complement bo ON m.id_boisson = bo.id
JOIN complement f ON m.id_frite = f.id
WHERE m.id = p_menu_id AND m.is_archive = FALSE AND b.is_archive = FALSE AND bo.is_archive = FALSE AND f.is_archive = FALSE;
RETURN COALESCE(v_prix, 0);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION commandes_en_cours_livreur(p_livreur_id INTEGER)
RETURNS INTEGER AS $$
BEGIN
RETURN (SELECT COUNT(*) FROM commande WHERE id_livreur = p_livreur_id AND etat_commande IN ('CONFIRMEE', 'EN_PREPARATION', 'PRETE'));
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION ca_periode(p_date_debut DATE, p_date_fin DATE)
RETURNS DECIMAL(10,2) AS $$
BEGIN
RETURN COALESCE((SELECT SUM(montant_total) FROM commande WHERE DATE(date_commande) BETWEEN p_date_debut AND p_date_fin AND etat_commande IN ('LIVREE', 'RETIREE', 'CONSOMMEE_SUR_PLACE')), 0);
END;
$$ LANGUAGE plpgsql;
