<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230407012922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidatures (id INT AUTO_INCREMENT NOT NULL, cv VARCHAR(100) NOT NULL, lettre VARCHAR(100) NOT NULL, date DATETIME DEFAULT NULL, etat VARCHAR(50) DEFAULT NULL, idCandidat INT DEFAULT NULL, idOffre INT DEFAULT NULL, INDEX IDX_DE57CF6639169E2A (idCandidat), INDEX IDX_DE57CF66B842C572 (idOffre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (idCommentaire INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(254) NOT NULL, idProjet INT DEFAULT NULL, idUser INT DEFAULT NULL, INDEX IDX_D9BEC0C433043433 (idProjet), INDEX IDX_D9BEC0C4FE6E88D7 (idUser), PRIMARY KEY(idCommentaire)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comptes (idcompte INT AUTO_INCREMENT NOT NULL, photo VARCHAR(200) DEFAULT NULL, diplome VARCHAR(50) DEFAULT NULL, datediplome DATETIME DEFAULT NULL, entreprise VARCHAR(50) DEFAULT NULL, experience VARCHAR(50) DEFAULT NULL, domaine VARCHAR(200) NOT NULL, poste VARCHAR(200) DEFAULT NULL, idUtilisateur INT DEFAULT NULL, INDEX IDX_567358015D419CCB (idUtilisateur), PRIMARY KEY(idcompte)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entretiens (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(10) DEFAULT NULL, date DATETIME DEFAULT NULL, heure VARCHAR(10) NOT NULL, lieu VARCHAR(50) NOT NULL, idCandidature INT DEFAULT NULL, idGuide INT DEFAULT NULL, INDEX IDX_7D23AC17A3662CC0 (idCandidature), INDEX IDX_7D23AC17DD5A8428 (idGuide), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE freelancersp (id INT AUTO_INCREMENT NOT NULL, idProjet INT DEFAULT NULL, idUtilisateur INT DEFAULT NULL, INDEX IDX_72BD9E7E33043433 (idProjet), INDEX IDX_72BD9E7E5D419CCB (idUtilisateur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guidesentretiens (idGuide INT AUTO_INCREMENT NOT NULL, domaine VARCHAR(30) NOT NULL, specialite VARCHAR(30) NOT NULL, support VARCHAR(200) NOT NULL, note DOUBLE PRECISION NOT NULL, nombrenotes INT NOT NULL, PRIMARY KEY(idGuide)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projets (idProjet INT AUTO_INCREMENT NOT NULL, theme VARCHAR(254) NOT NULL, description VARCHAR(254) NOT NULL, duree INT NOT NULL, datedebut DATETIME NOT NULL, datefin DATETIME NOT NULL, nom VARCHAR(254) NOT NULL, note INT NOT NULL, idSecteur INT DEFAULT NULL, idResponsable INT DEFAULT NULL, INDEX IDX_B454C1DB90FC4EED (idSecteur), INDEX IDX_B454C1DB120FF27F (idResponsable), PRIMARY KEY(idProjet)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, secteur VARCHAR(100) NOT NULL, specialite VARCHAR(100) NOT NULL, nom VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quizquestions (id INT AUTO_INCREMENT NOT NULL, question VARCHAR(100) NOT NULL, option1 VARCHAR(100) NOT NULL, option2 VARCHAR(100) NOT NULL, option3 VARCHAR(100) NOT NULL, reponse VARCHAR(100) NOT NULL, idQuiz INT DEFAULT NULL, INDEX IDX_72D8E8F0D7EFA40C (idQuiz), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quizscores (id INT AUTO_INCREMENT NOT NULL, score INT NOT NULL, date DATETIME DEFAULT NULL, idQuiz INT DEFAULT NULL, idCandidat INT DEFAULT NULL, INDEX IDX_711BA242D7EFA40C (idQuiz), INDEX IDX_711BA24239169E2A (idCandidat), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (idRole INT AUTO_INCREMENT NOT NULL, description VARCHAR(200) NOT NULL, PRIMARY KEY(idRole)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE secteurs (idSecteur INT AUTO_INCREMENT NOT NULL, description VARCHAR(254) NOT NULL, PRIMARY KEY(idSecteur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visitesguides (id INT AUTO_INCREMENT NOT NULL, date DATETIME DEFAULT NULL, idCompte INT DEFAULT NULL, idGuide INT DEFAULT NULL, INDEX IDX_FEDD6AF6ACE7FAFA (idCompte), INDEX IDX_FEDD6AF6DD5A8428 (idGuide), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT FK_DE57CF6639169E2A FOREIGN KEY (idCandidat) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT FK_DE57CF66B842C572 FOREIGN KEY (idOffre) REFERENCES offre (idOffre)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C433043433 FOREIGN KEY (idProjet) REFERENCES projets (idProjet)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4FE6E88D7 FOREIGN KEY (idUser) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE comptes ADD CONSTRAINT FK_567358015D419CCB FOREIGN KEY (idUtilisateur) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE entretiens ADD CONSTRAINT FK_7D23AC17A3662CC0 FOREIGN KEY (idCandidature) REFERENCES candidatures (id)');
        $this->addSql('ALTER TABLE entretiens ADD CONSTRAINT FK_7D23AC17DD5A8428 FOREIGN KEY (idGuide) REFERENCES guidesentretiens (idGuide)');
        $this->addSql('ALTER TABLE freelancersp ADD CONSTRAINT FK_72BD9E7E33043433 FOREIGN KEY (idProjet) REFERENCES projets (idProjet)');
        $this->addSql('ALTER TABLE freelancersp ADD CONSTRAINT FK_72BD9E7E5D419CCB FOREIGN KEY (idUtilisateur) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE projets ADD CONSTRAINT FK_B454C1DB90FC4EED FOREIGN KEY (idSecteur) REFERENCES secteurs (idSecteur)');
        $this->addSql('ALTER TABLE projets ADD CONSTRAINT FK_B454C1DB120FF27F FOREIGN KEY (idResponsable) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE quizquestions ADD CONSTRAINT FK_72D8E8F0D7EFA40C FOREIGN KEY (idQuiz) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quizscores ADD CONSTRAINT FK_711BA242D7EFA40C FOREIGN KEY (idQuiz) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quizscores ADD CONSTRAINT FK_711BA24239169E2A FOREIGN KEY (idCandidat) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE visitesguides ADD CONSTRAINT FK_FEDD6AF6ACE7FAFA FOREIGN KEY (idCompte) REFERENCES comptes (idcompte)');
        $this->addSql('ALTER TABLE visitesguides ADD CONSTRAINT FK_FEDD6AF6DD5A8428 FOREIGN KEY (idGuide) REFERENCES guidesentretiens (idGuide)');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY fk_type');
        $this->addSql('ALTER TABLE offre CHANGE idtype idtype INT DEFAULT NULL, CHANGE dateExpiration dateexpiration DATETIME NOT NULL, CHANGE idrecruteur idrecruteur INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F55C8CE79 FOREIGN KEY (idrecruteur) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_AF86866F55C8CE79 ON offre (idrecruteur)');
        $this->addSql('DROP INDEX fk_type ON offre');
        $this->addSql('CREATE INDEX IDX_AF86866F5F11A689 ON offre (idtype)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT fk_type FOREIGN KEY (idtype) REFERENCES typeoffre (idtype)');
        $this->addSql('ALTER TABLE utilisateur ADD nom VARCHAR(30) NOT NULL, ADD prenom VARCHAR(30) NOT NULL, ADD tel VARCHAR(50) NOT NULL, ADD adresse VARCHAR(50) NOT NULL, ADD mdp VARCHAR(100) NOT NULL, ADD datenaissance VARCHAR(255) NOT NULL, ADD salt VARCHAR(1000) NOT NULL, ADD idRole INT DEFAULT NULL, CHANGE email email VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B32494D4F4 FOREIGN KEY (idRole) REFERENCES role (idRole)');
        $this->addSql('CREATE INDEX IDX_1D1C63B32494D4F4 ON utilisateur (idRole)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B32494D4F4');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY FK_DE57CF6639169E2A');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY FK_DE57CF66B842C572');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C433043433');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4FE6E88D7');
        $this->addSql('ALTER TABLE comptes DROP FOREIGN KEY FK_567358015D419CCB');
        $this->addSql('ALTER TABLE entretiens DROP FOREIGN KEY FK_7D23AC17A3662CC0');
        $this->addSql('ALTER TABLE entretiens DROP FOREIGN KEY FK_7D23AC17DD5A8428');
        $this->addSql('ALTER TABLE freelancersp DROP FOREIGN KEY FK_72BD9E7E33043433');
        $this->addSql('ALTER TABLE freelancersp DROP FOREIGN KEY FK_72BD9E7E5D419CCB');
        $this->addSql('ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DB90FC4EED');
        $this->addSql('ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DB120FF27F');
        $this->addSql('ALTER TABLE quizquestions DROP FOREIGN KEY FK_72D8E8F0D7EFA40C');
        $this->addSql('ALTER TABLE quizscores DROP FOREIGN KEY FK_711BA242D7EFA40C');
        $this->addSql('ALTER TABLE quizscores DROP FOREIGN KEY FK_711BA24239169E2A');
        $this->addSql('ALTER TABLE visitesguides DROP FOREIGN KEY FK_FEDD6AF6ACE7FAFA');
        $this->addSql('ALTER TABLE visitesguides DROP FOREIGN KEY FK_FEDD6AF6DD5A8428');
        $this->addSql('DROP TABLE candidatures');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE comptes');
        $this->addSql('DROP TABLE entretiens');
        $this->addSql('DROP TABLE freelancersp');
        $this->addSql('DROP TABLE guidesentretiens');
        $this->addSql('DROP TABLE projets');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quizquestions');
        $this->addSql('DROP TABLE quizscores');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE secteurs');
        $this->addSql('DROP TABLE visitesguides');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F55C8CE79');
        $this->addSql('DROP INDEX IDX_AF86866F55C8CE79 ON offre');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F5F11A689');
        $this->addSql('ALTER TABLE offre CHANGE idtype idtype INT NOT NULL, CHANGE idrecruteur idrecruteur INT NOT NULL, CHANGE dateexpiration dateExpiration DATE NOT NULL');
        $this->addSql('DROP INDEX idx_af86866f5f11a689 ON offre');
        $this->addSql('CREATE INDEX fk_type ON offre (idtype)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F5F11A689 FOREIGN KEY (idtype) REFERENCES typeoffre (idtype)');
        $this->addSql('DROP INDEX IDX_1D1C63B32494D4F4 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP nom, DROP prenom, DROP tel, DROP adresse, DROP mdp, DROP datenaissance, DROP salt, DROP idRole, CHANGE email email VARCHAR(254) NOT NULL');
    }
}
