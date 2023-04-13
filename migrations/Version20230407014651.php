<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230407014651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY fk_offre_cand');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY fk_user_cand');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY fk_offre_cand');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY fk_user_cand');
        $this->addSql('ALTER TABLE candidatures CHANGE idCandidat idCandidat INT DEFAULT NULL, CHANGE idOffre idOffre INT DEFAULT NULL, CHANGE date date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT FK_DE57CF6639169E2A FOREIGN KEY (idCandidat) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT FK_DE57CF66B842C572 FOREIGN KEY (idOffre) REFERENCES offre (idOffre)');
        $this->addSql('DROP INDEX fk_user_cand ON candidatures');
        $this->addSql('CREATE INDEX IDX_DE57CF6639169E2A ON candidatures (idCandidat)');
        $this->addSql('DROP INDEX fk_offre_cand ON candidatures');
        $this->addSql('CREATE INDEX IDX_DE57CF66B842C572 ON candidatures (idOffre)');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT fk_offre_cand FOREIGN KEY (idOffre) REFERENCES offre (idOffre) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT fk_user_cand FOREIGN KEY (idCandidat) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_IdUser');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_IdProjet');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_IdUser');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_IdProjet');
        $this->addSql('ALTER TABLE commentaires CHANGE idUser idUser INT DEFAULT NULL, CHANGE idProjet idProjet INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C433043433 FOREIGN KEY (idProjet) REFERENCES projets (idProjet)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4FE6E88D7 FOREIGN KEY (idUser) REFERENCES utilisateur (id)');
        $this->addSql('DROP INDEX fk_idprojet ON commentaires');
        $this->addSql('CREATE INDEX IDX_D9BEC0C433043433 ON commentaires (idProjet)');
        $this->addSql('DROP INDEX fk_iduser ON commentaires');
        $this->addSql('CREATE INDEX IDX_D9BEC0C4FE6E88D7 ON commentaires (idUser)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_IdUser FOREIGN KEY (idUser) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_IdProjet FOREIGN KEY (idProjet) REFERENCES projets (idProjet) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comptes DROP FOREIGN KEY fk_user');
        $this->addSql('ALTER TABLE comptes DROP FOREIGN KEY fk_user');
        $this->addSql('ALTER TABLE comptes CHANGE dateDiplome datediplome DATETIME DEFAULT NULL, CHANGE idUtilisateur idUtilisateur INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comptes ADD CONSTRAINT FK_567358015D419CCB FOREIGN KEY (idUtilisateur) REFERENCES utilisateur (id)');
        $this->addSql('DROP INDEX fk_user ON comptes');
        $this->addSql('CREATE INDEX IDX_567358015D419CCB ON comptes (idUtilisateur)');
        $this->addSql('ALTER TABLE comptes ADD CONSTRAINT fk_user FOREIGN KEY (idUtilisateur) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entretiens DROP FOREIGN KEY fk_entretien_guide');
        $this->addSql('ALTER TABLE entretiens DROP FOREIGN KEY fk_entretien_candidature');
        $this->addSql('ALTER TABLE entretiens DROP FOREIGN KEY fk_entretien_guide');
        $this->addSql('ALTER TABLE entretiens DROP FOREIGN KEY fk_entretien_candidature');
        $this->addSql('ALTER TABLE entretiens CHANGE date date DATETIME DEFAULT NULL, CHANGE idCandidature idCandidature INT DEFAULT NULL, CHANGE idGuide idGuide INT DEFAULT NULL');
        $this->addSql('ALTER TABLE entretiens ADD CONSTRAINT FK_7D23AC17A3662CC0 FOREIGN KEY (idCandidature) REFERENCES candidatures (id)');
        $this->addSql('ALTER TABLE entretiens ADD CONSTRAINT FK_7D23AC17DD5A8428 FOREIGN KEY (idGuide) REFERENCES guidesentretiens (idGuide)');
        $this->addSql('DROP INDEX fk_candidature ON entretiens');
        $this->addSql('CREATE INDEX IDX_7D23AC17A3662CC0 ON entretiens (idCandidature)');
        $this->addSql('DROP INDEX fk_entretien_guide ON entretiens');
        $this->addSql('CREATE INDEX IDX_7D23AC17DD5A8428 ON entretiens (idGuide)');
        $this->addSql('ALTER TABLE entretiens ADD CONSTRAINT fk_entretien_guide FOREIGN KEY (idGuide) REFERENCES guidesentretiens (idGuide) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entretiens ADD CONSTRAINT fk_entretien_candidature FOREIGN KEY (idCandidature) REFERENCES candidatures (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE freelancersp DROP FOREIGN KEY fk_freelancersp_projet');
        $this->addSql('ALTER TABLE freelancersp DROP FOREIGN KEY fk_freelancersp_user');
        $this->addSql('ALTER TABLE freelancersp DROP FOREIGN KEY fk_freelancersp_projet');
        $this->addSql('ALTER TABLE freelancersp DROP FOREIGN KEY fk_freelancersp_user');
        $this->addSql('ALTER TABLE freelancersp CHANGE idProjet idProjet INT DEFAULT NULL, CHANGE idUtilisateur idUtilisateur INT DEFAULT NULL');
        $this->addSql('ALTER TABLE freelancersp ADD CONSTRAINT FK_72BD9E7E33043433 FOREIGN KEY (idProjet) REFERENCES projets (idProjet)');
        $this->addSql('ALTER TABLE freelancersp ADD CONSTRAINT FK_72BD9E7E5D419CCB FOREIGN KEY (idUtilisateur) REFERENCES utilisateur (id)');
        $this->addSql('DROP INDEX fk_freelancersp_projet ON freelancersp');
        $this->addSql('CREATE INDEX IDX_72BD9E7E33043433 ON freelancersp (idProjet)');
        $this->addSql('DROP INDEX fk_freelancersp_user ON freelancersp');
        $this->addSql('CREATE INDEX IDX_72BD9E7E5D419CCB ON freelancersp (idUtilisateur)');
        $this->addSql('ALTER TABLE freelancersp ADD CONSTRAINT fk_freelancersp_projet FOREIGN KEY (idProjet) REFERENCES projets (idProjet) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE freelancersp ADD CONSTRAINT fk_freelancersp_user FOREIGN KEY (idUtilisateur) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY fk_offre_user');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY fk_type');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY fk_offre_user');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY fk_type');
        $this->addSql('ALTER TABLE offre CHANGE idrecruteur idrecruteur INT DEFAULT NULL, CHANGE idtype idtype INT DEFAULT NULL, CHANGE dateExpiration dateexpiration DATETIME NOT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F5F11A689 FOREIGN KEY (idtype) REFERENCES typeoffre (idtype)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F55C8CE79 FOREIGN KEY (idrecruteur) REFERENCES utilisateur (id)');
        $this->addSql('DROP INDEX fk_type ON offre');
        $this->addSql('CREATE INDEX IDX_AF86866F5F11A689 ON offre (idtype)');
        $this->addSql('DROP INDEX fk_offre_user ON offre');
        $this->addSql('CREATE INDEX IDX_AF86866F55C8CE79 ON offre (idrecruteur)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT fk_offre_user FOREIGN KEY (idrecruteur) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT fk_type FOREIGN KEY (idtype) REFERENCES typeoffre (idtype) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projets DROP FOREIGN KEY fk_projet_user');
        $this->addSql('ALTER TABLE projets DROP FOREIGN KEY FK_Secteur');
        $this->addSql('ALTER TABLE projets DROP FOREIGN KEY fk_projet_user');
        $this->addSql('ALTER TABLE projets DROP FOREIGN KEY FK_Secteur');
        $this->addSql('ALTER TABLE projets CHANGE dateDebut datedebut DATETIME NOT NULL, CHANGE dateFin datefin DATETIME NOT NULL, CHANGE idSecteur idSecteur INT DEFAULT NULL, CHANGE idResponsable idResponsable INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projets ADD CONSTRAINT FK_B454C1DB90FC4EED FOREIGN KEY (idSecteur) REFERENCES secteurs (idSecteur)');
        $this->addSql('ALTER TABLE projets ADD CONSTRAINT FK_B454C1DB120FF27F FOREIGN KEY (idResponsable) REFERENCES utilisateur (id)');
        $this->addSql('DROP INDEX fk_secteur ON projets');
        $this->addSql('CREATE INDEX IDX_B454C1DB90FC4EED ON projets (idSecteur)');
        $this->addSql('DROP INDEX fk_projet_user ON projets');
        $this->addSql('CREATE INDEX IDX_B454C1DB120FF27F ON projets (idResponsable)');
        $this->addSql('ALTER TABLE projets ADD CONSTRAINT fk_projet_user FOREIGN KEY (idResponsable) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projets ADD CONSTRAINT FK_Secteur FOREIGN KEY (idSecteur) REFERENCES secteurs (idSecteur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX specialite ON quiz');
        $this->addSql('ALTER TABLE quizquestions DROP FOREIGN KEY fk_quiz_questions');
        $this->addSql('ALTER TABLE quizquestions DROP FOREIGN KEY fk_quiz_questions');
        $this->addSql('ALTER TABLE quizquestions CHANGE idQuiz idQuiz INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quizquestions ADD CONSTRAINT FK_72D8E8F0D7EFA40C FOREIGN KEY (idQuiz) REFERENCES quiz (id)');
        $this->addSql('DROP INDEX fk_quiz_questions ON quizquestions');
        $this->addSql('CREATE INDEX IDX_72D8E8F0D7EFA40C ON quizquestions (idQuiz)');
        $this->addSql('ALTER TABLE quizquestions ADD CONSTRAINT fk_quiz_questions FOREIGN KEY (idQuiz) REFERENCES quiz (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizscores DROP FOREIGN KEY fk_quizzscore_user');
        $this->addSql('ALTER TABLE quizscores DROP FOREIGN KEY fk_quizzscore_quiz');
        $this->addSql('ALTER TABLE quizscores DROP FOREIGN KEY fk_quizzscore_user');
        $this->addSql('ALTER TABLE quizscores DROP FOREIGN KEY fk_quizzscore_quiz');
        $this->addSql('ALTER TABLE quizscores CHANGE idCandidat idCandidat INT DEFAULT NULL, CHANGE idQuiz idQuiz INT DEFAULT NULL, CHANGE date date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE quizscores ADD CONSTRAINT FK_711BA242D7EFA40C FOREIGN KEY (idQuiz) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quizscores ADD CONSTRAINT FK_711BA24239169E2A FOREIGN KEY (idCandidat) REFERENCES utilisateur (id)');
        $this->addSql('DROP INDEX fk_quiz_score ON quizscores');
        $this->addSql('CREATE INDEX IDX_711BA242D7EFA40C ON quizscores (idQuiz)');
        $this->addSql('DROP INDEX fk_quizzscore_user ON quizscores');
        $this->addSql('CREATE INDEX IDX_711BA24239169E2A ON quizscores (idCandidat)');
        $this->addSql('ALTER TABLE quizscores ADD CONSTRAINT fk_quizzscore_user FOREIGN KEY (idCandidat) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizscores ADD CONSTRAINT fk_quizzscore_quiz FOREIGN KEY (idQuiz) REFERENCES quiz (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX description ON secteurs');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY fk_role');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY fk_role');
        $this->addSql('ALTER TABLE utilisateur CHANGE dateNaissance datenaissance VARCHAR(255) NOT NULL, CHANGE idRole idRole INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B32494D4F4 FOREIGN KEY (idRole) REFERENCES role (idRole)');
        $this->addSql('DROP INDEX fk_role ON utilisateur');
        $this->addSql('CREATE INDEX IDX_1D1C63B32494D4F4 ON utilisateur (idRole)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT fk_role FOREIGN KEY (idRole) REFERENCES role (idRole) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visitesguides DROP FOREIGN KEY fk_visite_guide');
        $this->addSql('ALTER TABLE visitesguides DROP FOREIGN KEY fk_visite_compte');
        $this->addSql('ALTER TABLE visitesguides DROP FOREIGN KEY fk_visite_guide');
        $this->addSql('ALTER TABLE visitesguides DROP FOREIGN KEY fk_visite_compte');
        $this->addSql('ALTER TABLE visitesguides CHANGE date date DATETIME DEFAULT NULL, CHANGE idCompte idCompte INT DEFAULT NULL, CHANGE idGuide idGuide INT DEFAULT NULL');
        $this->addSql('ALTER TABLE visitesguides ADD CONSTRAINT FK_FEDD6AF6ACE7FAFA FOREIGN KEY (idCompte) REFERENCES comptes (idcompte)');
        $this->addSql('ALTER TABLE visitesguides ADD CONSTRAINT FK_FEDD6AF6DD5A8428 FOREIGN KEY (idGuide) REFERENCES guidesentretiens (idGuide)');
        $this->addSql('DROP INDEX fk_compte ON visitesguides');
        $this->addSql('CREATE INDEX IDX_FEDD6AF6ACE7FAFA ON visitesguides (idCompte)');
        $this->addSql('DROP INDEX fk_guide ON visitesguides');
        $this->addSql('CREATE INDEX IDX_FEDD6AF6DD5A8428 ON visitesguides (idGuide)');
        $this->addSql('ALTER TABLE visitesguides ADD CONSTRAINT fk_visite_guide FOREIGN KEY (idGuide) REFERENCES guidesentretiens (idGuide) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visitesguides ADD CONSTRAINT fk_visite_compte FOREIGN KEY (idCompte) REFERENCES comptes (idcompte) ON UPDATE CASCADE ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY FK_DE57CF6639169E2A');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY FK_DE57CF66B842C572');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY FK_DE57CF6639169E2A');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY FK_DE57CF66B842C572');
        $this->addSql('ALTER TABLE candidatures CHANGE date date DATE DEFAULT NULL, CHANGE idCandidat idCandidat INT NOT NULL, CHANGE idOffre idOffre INT NOT NULL');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT fk_offre_cand FOREIGN KEY (idOffre) REFERENCES offre (idOffre) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT fk_user_cand FOREIGN KEY (idCandidat) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_de57cf6639169e2a ON candidatures');
        $this->addSql('CREATE INDEX fk_user_cand ON candidatures (idCandidat)');
        $this->addSql('DROP INDEX idx_de57cf66b842c572 ON candidatures');
        $this->addSql('CREATE INDEX fk_offre_cand ON candidatures (idOffre)');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT FK_DE57CF6639169E2A FOREIGN KEY (idCandidat) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT FK_DE57CF66B842C572 FOREIGN KEY (idOffre) REFERENCES offre (idOffre)');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C433043433');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4FE6E88D7');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C433043433');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4FE6E88D7');
        $this->addSql('ALTER TABLE commentaires CHANGE idProjet idProjet INT NOT NULL, CHANGE idUser idUser INT NOT NULL');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_IdUser FOREIGN KEY (idUser) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_IdProjet FOREIGN KEY (idProjet) REFERENCES projets (idProjet) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_d9bec0c433043433 ON commentaires');
        $this->addSql('CREATE INDEX FK_IdProjet ON commentaires (idProjet)');
        $this->addSql('DROP INDEX idx_d9bec0c4fe6e88d7 ON commentaires');
        $this->addSql('CREATE INDEX FK_IdUser ON commentaires (idUser)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C433043433 FOREIGN KEY (idProjet) REFERENCES projets (idProjet)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4FE6E88D7 FOREIGN KEY (idUser) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE comptes DROP FOREIGN KEY FK_567358015D419CCB');
        $this->addSql('ALTER TABLE comptes DROP FOREIGN KEY FK_567358015D419CCB');
        $this->addSql('ALTER TABLE comptes CHANGE datediplome dateDiplome DATE DEFAULT NULL, CHANGE idUtilisateur idUtilisateur INT NOT NULL');
        $this->addSql('ALTER TABLE comptes ADD CONSTRAINT fk_user FOREIGN KEY (idUtilisateur) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_567358015d419ccb ON comptes');
        $this->addSql('CREATE INDEX fk_user ON comptes (idUtilisateur)');
        $this->addSql('ALTER TABLE comptes ADD CONSTRAINT FK_567358015D419CCB FOREIGN KEY (idUtilisateur) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE entretiens DROP FOREIGN KEY FK_7D23AC17A3662CC0');
        $this->addSql('ALTER TABLE entretiens DROP FOREIGN KEY FK_7D23AC17DD5A8428');
        $this->addSql('ALTER TABLE entretiens DROP FOREIGN KEY FK_7D23AC17A3662CC0');
        $this->addSql('ALTER TABLE entretiens DROP FOREIGN KEY FK_7D23AC17DD5A8428');
        $this->addSql('ALTER TABLE entretiens CHANGE date date DATE DEFAULT NULL, CHANGE idCandidature idCandidature INT NOT NULL, CHANGE idGuide idGuide INT NOT NULL');
        $this->addSql('ALTER TABLE entretiens ADD CONSTRAINT fk_entretien_guide FOREIGN KEY (idGuide) REFERENCES guidesentretiens (idGuide) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entretiens ADD CONSTRAINT fk_entretien_candidature FOREIGN KEY (idCandidature) REFERENCES candidatures (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_7d23ac17dd5a8428 ON entretiens');
        $this->addSql('CREATE INDEX fk_entretien_guide ON entretiens (idGuide)');
        $this->addSql('DROP INDEX idx_7d23ac17a3662cc0 ON entretiens');
        $this->addSql('CREATE INDEX fk_candidature ON entretiens (idCandidature)');
        $this->addSql('ALTER TABLE entretiens ADD CONSTRAINT FK_7D23AC17A3662CC0 FOREIGN KEY (idCandidature) REFERENCES candidatures (id)');
        $this->addSql('ALTER TABLE entretiens ADD CONSTRAINT FK_7D23AC17DD5A8428 FOREIGN KEY (idGuide) REFERENCES guidesentretiens (idGuide)');
        $this->addSql('ALTER TABLE freelancersp DROP FOREIGN KEY FK_72BD9E7E33043433');
        $this->addSql('ALTER TABLE freelancersp DROP FOREIGN KEY FK_72BD9E7E5D419CCB');
        $this->addSql('ALTER TABLE freelancersp DROP FOREIGN KEY FK_72BD9E7E33043433');
        $this->addSql('ALTER TABLE freelancersp DROP FOREIGN KEY FK_72BD9E7E5D419CCB');
        $this->addSql('ALTER TABLE freelancersp CHANGE idProjet idProjet INT NOT NULL, CHANGE idUtilisateur idUtilisateur INT NOT NULL');
        $this->addSql('ALTER TABLE freelancersp ADD CONSTRAINT fk_freelancersp_projet FOREIGN KEY (idProjet) REFERENCES projets (idProjet) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE freelancersp ADD CONSTRAINT fk_freelancersp_user FOREIGN KEY (idUtilisateur) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_72bd9e7e33043433 ON freelancersp');
        $this->addSql('CREATE INDEX fk_freelancersp_projet ON freelancersp (idProjet)');
        $this->addSql('DROP INDEX idx_72bd9e7e5d419ccb ON freelancersp');
        $this->addSql('CREATE INDEX fk_freelancersp_user ON freelancersp (idUtilisateur)');
        $this->addSql('ALTER TABLE freelancersp ADD CONSTRAINT FK_72BD9E7E33043433 FOREIGN KEY (idProjet) REFERENCES projets (idProjet)');
        $this->addSql('ALTER TABLE freelancersp ADD CONSTRAINT FK_72BD9E7E5D419CCB FOREIGN KEY (idUtilisateur) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F5F11A689');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F55C8CE79');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F5F11A689');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F55C8CE79');
        $this->addSql('ALTER TABLE offre CHANGE idtype idtype INT NOT NULL, CHANGE idrecruteur idrecruteur INT NOT NULL, CHANGE dateexpiration dateExpiration DATE NOT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT fk_offre_user FOREIGN KEY (idrecruteur) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT fk_type FOREIGN KEY (idtype) REFERENCES typeoffre (idtype) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_af86866f5f11a689 ON offre');
        $this->addSql('CREATE INDEX fk_type ON offre (idtype)');
        $this->addSql('DROP INDEX idx_af86866f55c8ce79 ON offre');
        $this->addSql('CREATE INDEX fk_offre_user ON offre (idrecruteur)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F5F11A689 FOREIGN KEY (idtype) REFERENCES typeoffre (idtype)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F55C8CE79 FOREIGN KEY (idrecruteur) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DB90FC4EED');
        $this->addSql('ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DB120FF27F');
        $this->addSql('ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DB90FC4EED');
        $this->addSql('ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DB120FF27F');
        $this->addSql('ALTER TABLE projets CHANGE datedebut dateDebut DATE NOT NULL, CHANGE datefin dateFin DATE NOT NULL, CHANGE idSecteur idSecteur INT NOT NULL, CHANGE idResponsable idResponsable INT NOT NULL');
        $this->addSql('ALTER TABLE projets ADD CONSTRAINT fk_projet_user FOREIGN KEY (idResponsable) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projets ADD CONSTRAINT FK_Secteur FOREIGN KEY (idSecteur) REFERENCES secteurs (idSecteur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_b454c1db90fc4eed ON projets');
        $this->addSql('CREATE INDEX FK_Secteur ON projets (idSecteur)');
        $this->addSql('DROP INDEX idx_b454c1db120ff27f ON projets');
        $this->addSql('CREATE INDEX fk_projet_user ON projets (idResponsable)');
        $this->addSql('ALTER TABLE projets ADD CONSTRAINT FK_B454C1DB90FC4EED FOREIGN KEY (idSecteur) REFERENCES secteurs (idSecteur)');
        $this->addSql('ALTER TABLE projets ADD CONSTRAINT FK_B454C1DB120FF27F FOREIGN KEY (idResponsable) REFERENCES utilisateur (id)');
        $this->addSql('CREATE UNIQUE INDEX specialite ON quiz (specialite)');
        $this->addSql('ALTER TABLE quizquestions DROP FOREIGN KEY FK_72D8E8F0D7EFA40C');
        $this->addSql('ALTER TABLE quizquestions DROP FOREIGN KEY FK_72D8E8F0D7EFA40C');
        $this->addSql('ALTER TABLE quizquestions CHANGE idQuiz idQuiz INT NOT NULL');
        $this->addSql('ALTER TABLE quizquestions ADD CONSTRAINT fk_quiz_questions FOREIGN KEY (idQuiz) REFERENCES quiz (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_72d8e8f0d7efa40c ON quizquestions');
        $this->addSql('CREATE INDEX fk_quiz_questions ON quizquestions (idQuiz)');
        $this->addSql('ALTER TABLE quizquestions ADD CONSTRAINT FK_72D8E8F0D7EFA40C FOREIGN KEY (idQuiz) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quizscores DROP FOREIGN KEY FK_711BA242D7EFA40C');
        $this->addSql('ALTER TABLE quizscores DROP FOREIGN KEY FK_711BA24239169E2A');
        $this->addSql('ALTER TABLE quizscores DROP FOREIGN KEY FK_711BA242D7EFA40C');
        $this->addSql('ALTER TABLE quizscores DROP FOREIGN KEY FK_711BA24239169E2A');
        $this->addSql('ALTER TABLE quizscores CHANGE date date DATE DEFAULT NULL, CHANGE idQuiz idQuiz INT NOT NULL, CHANGE idCandidat idCandidat INT NOT NULL');
        $this->addSql('ALTER TABLE quizscores ADD CONSTRAINT fk_quizzscore_user FOREIGN KEY (idCandidat) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizscores ADD CONSTRAINT fk_quizzscore_quiz FOREIGN KEY (idQuiz) REFERENCES quiz (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_711ba242d7efa40c ON quizscores');
        $this->addSql('CREATE INDEX fk_quiz_score ON quizscores (idQuiz)');
        $this->addSql('DROP INDEX idx_711ba24239169e2a ON quizscores');
        $this->addSql('CREATE INDEX fk_quizzscore_user ON quizscores (idCandidat)');
        $this->addSql('ALTER TABLE quizscores ADD CONSTRAINT FK_711BA242D7EFA40C FOREIGN KEY (idQuiz) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quizscores ADD CONSTRAINT FK_711BA24239169E2A FOREIGN KEY (idCandidat) REFERENCES utilisateur (id)');
        $this->addSql('CREATE UNIQUE INDEX description ON secteurs (description)');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B32494D4F4');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B32494D4F4');
        $this->addSql('ALTER TABLE utilisateur CHANGE datenaissance dateNaissance DATE NOT NULL, CHANGE idRole idRole INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT fk_role FOREIGN KEY (idRole) REFERENCES role (idRole) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_1d1c63b32494d4f4 ON utilisateur');
        $this->addSql('CREATE INDEX fk_role ON utilisateur (idRole)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B32494D4F4 FOREIGN KEY (idRole) REFERENCES role (idRole)');
        $this->addSql('ALTER TABLE visitesguides DROP FOREIGN KEY FK_FEDD6AF6ACE7FAFA');
        $this->addSql('ALTER TABLE visitesguides DROP FOREIGN KEY FK_FEDD6AF6DD5A8428');
        $this->addSql('ALTER TABLE visitesguides DROP FOREIGN KEY FK_FEDD6AF6ACE7FAFA');
        $this->addSql('ALTER TABLE visitesguides DROP FOREIGN KEY FK_FEDD6AF6DD5A8428');
        $this->addSql('ALTER TABLE visitesguides CHANGE date date DATE DEFAULT NULL, CHANGE idCompte idCompte INT NOT NULL, CHANGE idGuide idGuide INT NOT NULL');
        $this->addSql('ALTER TABLE visitesguides ADD CONSTRAINT fk_visite_guide FOREIGN KEY (idGuide) REFERENCES guidesentretiens (idGuide) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visitesguides ADD CONSTRAINT fk_visite_compte FOREIGN KEY (idCompte) REFERENCES comptes (idcompte) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_fedd6af6ace7fafa ON visitesguides');
        $this->addSql('CREATE INDEX fk_compte ON visitesguides (idCompte)');
        $this->addSql('DROP INDEX idx_fedd6af6dd5a8428 ON visitesguides');
        $this->addSql('CREATE INDEX fk_guide ON visitesguides (idGuide)');
        $this->addSql('ALTER TABLE visitesguides ADD CONSTRAINT FK_FEDD6AF6ACE7FAFA FOREIGN KEY (idCompte) REFERENCES comptes (idcompte)');
        $this->addSql('ALTER TABLE visitesguides ADD CONSTRAINT FK_FEDD6AF6DD5A8428 FOREIGN KEY (idGuide) REFERENCES guidesentretiens (idGuide)');
    }
}
