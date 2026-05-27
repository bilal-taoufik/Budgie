drop database if exists budgie;
create database budgie;
\c budgie;

create table duree (
    dur_id              serial,
    dur_type            varchar(20) not null check (dur_type in ('ponctuel', 'recurrent')),
    dur_interval_mois   int,
    primary key (dur_id)
);

create table abonnement (
    abo_id                  serial,
    abo_type                varchar(50) not null,
    abo_prix                decimal(10,2) not null,
    abo_limite_comptes      int,
    abo_limite_depenses     int,
    abo_limite_revenus      int,
    primary key (abo_id)
);

create table personne (
    prs_id          serial,
    prs_nom         varchar(50) not null,
    prs_prenom      varchar(50) not null,
    prs_email       varchar(100) not null unique,
    prs_password    varchar(255) not null,
    prs_adresse     varchar(255),
    prs_rib         varchar(23),
    prs_iban        varchar(34),
    prs_bic         varchar(11),
    prs_abo_id      int,
    primary key (prs_id),
    foreign key (prs_abo_id) references abonnement(abo_id)
);

create table compte (
    cmp_id                  serial,
    cmp_nom_appel           varchar(100) not null,
    cmp_description         varchar(255),
    cmp_date_creation       date not null,
    cmp_solde_initial       decimal(15,2) not null default 0,
    cmp_taux_remuneration   decimal(5,2),
    cmp_taux_imposition     decimal(5,2),
    cmp_prs_id              int not null,
    primary key (cmp_id),
    foreign key (cmp_prs_id) references personne(prs_id)
);

create table mouvement (
    mvt_id          serial,
    mvt_type        varchar(10) not null check (mvt_type in ('depense', 'revenu')),
    mvt_nom         varchar(100) not null,
    mvt_description varchar(255),
    mvt_montant     decimal(15,2) not null,
    mvt_date_debut  date not null,
    mvt_date_fin    date,
    mvt_cmp_id      int not null,
    mvt_dur_id      int,
    primary key (mvt_id),
    foreign key (mvt_cmp_id) references compte(cmp_id),
    foreign key (mvt_dur_id) references duree(dur_id)
);