/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: quiz
-- ------------------------------------------------------
-- Server version	11.7.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `Category`
--

DROP TABLE IF EXISTS `Category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Category_unique` (`name`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `DailyQuiz`
--

DROP TABLE IF EXISTS `DailyQuiz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `DailyQuiz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quizId` int(11) NOT NULL,
  `selectedDate` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `DailyQuiz_Quiz_id_fk` (`quizId`),
  CONSTRAINT `DailyQuiz_Quiz_id_fk` FOREIGN KEY (`quizId`) REFERENCES `Quiz` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LogActionType`
--

DROP TABLE IF EXISTS `LogActionType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `LogActionType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actionName` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LogUserAction`
--

DROP TABLE IF EXISTS `LogUserAction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `LogUserAction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL,
  `actionId` int(11) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `detail` tinytext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `action` (`actionId`),
  CONSTRAINT `LogUserAction_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`id`),
  CONSTRAINT `LogUserAction_ibfk_2` FOREIGN KEY (`actionId`) REFERENCES `LogActionType` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Question`
--

DROP TABLE IF EXISTS `Question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quizId` int(11) NOT NULL,
  `type` enum('guessFromImage','textareaAnswers','singleChoice','multipleChoice','fillTheGaps') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Question_ibfk_1` (`quizId`),
  CONSTRAINT `Question_ibfk_1` FOREIGN KEY (`quizId`) REFERENCES `Quiz` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `QuestionAnswers`
--

DROP TABLE IF EXISTS `QuestionAnswers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `QuestionAnswers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questionId` int(11) NOT NULL,
  `textContent` text DEFAULT NULL,
  `imageSrc` tinytext DEFAULT NULL,
  `videoSrc` tinytext DEFAULT NULL,
  `isValid` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `QuestionAnswers_ibfk_1` (`questionId`),
  CONSTRAINT `QuestionAnswers_ibfk_1` FOREIGN KEY (`questionId`) REFERENCES `Question` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `QuestionContent`
--

DROP TABLE IF EXISTS `QuestionContent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `QuestionContent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questionId` int(11) NOT NULL,
  `textContent` text DEFAULT NULL,
  `imageSrc` tinytext DEFAULT NULL,
  `videoSrc` tinytext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `QuestionContent_ibfk_1` (`questionId`),
  CONSTRAINT `QuestionContent_ibfk_1` FOREIGN KEY (`questionId`) REFERENCES `Question` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Quiz`
--

DROP TABLE IF EXISTS `Quiz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Quiz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ownerId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`) USING HASH,
  KEY `owner` (`ownerId`),
  CONSTRAINT `Quiz_ibfk_2` FOREIGN KEY (`ownerId`) REFERENCES `User` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `QuizAttempt`
--

DROP TABLE IF EXISTS `QuizAttempt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `QuizAttempt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quizId` int(11) DEFAULT NULL,
  `started` datetime NOT NULL,
  `ended` datetime DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `QuizAttempt_Quiz_id_fk` (`quizId`),
  KEY `QuizAttempt_User_id_fk` (`userId`),
  CONSTRAINT `QuizAttempt_Quiz_id_fk` FOREIGN KEY (`quizId`) REFERENCES `Quiz` (`id`) ON DELETE CASCADE,
  CONSTRAINT `QuizAttempt_User_id_fk` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `QuizCategory`
--

DROP TABLE IF EXISTS `QuizCategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `QuizCategory` (
  `quizId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  PRIMARY KEY (`quizId`,`categoryId`),
  KEY `QuizCategory_Category_id_fk` (`categoryId`),
  CONSTRAINT `QuizCategory_Category_id_fk` FOREIGN KEY (`categoryId`) REFERENCES `Category` (`id`) ON DELETE CASCADE,
  CONSTRAINT `QuizCategory_Quiz_id_fk` FOREIGN KEY (`quizId`) REFERENCES `Quiz` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext DEFAULT NULL,
  `email` tinytext NOT NULL,
  `passwordHash` tinytext DEFAULT NULL,
  `type` enum('administrator','moderator','user') DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `UserAnswer`
--

DROP TABLE IF EXISTS `UserAnswer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `UserAnswer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attemptId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `questionId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `UserAnswer_ibfk_1` (`attemptId`),
  KEY `UserAnswer_ibfk_2` (`userId`),
  KEY `UserAnswer_ibfk_3` (`questionId`),
  CONSTRAINT `UserAnswer_ibfk_1` FOREIGN KEY (`attemptId`) REFERENCES `QuizAttempt` (`id`) ON DELETE CASCADE,
  CONSTRAINT `UserAnswer_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  CONSTRAINT `UserAnswer_ibfk_3` FOREIGN KEY (`questionId`) REFERENCES `Question` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `UserAnswerContent`
--

DROP TABLE IF EXISTS `UserAnswerContent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `UserAnswerContent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userAnswerId` int(11) NOT NULL,
  `textContent` text DEFAULT NULL,
  `numericContent` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `UserAnswerContent_ibfk_1` (`userAnswerId`),
  CONSTRAINT `UserAnswerContent_ibfk_1` FOREIGN KEY (`userAnswerId`) REFERENCES `UserAnswer` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `UserAvatar`
--

DROP TABLE IF EXISTS `UserAvatar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `UserAvatar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `url` tinytext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `UserAvatar_User_id_fk` (`userId`),
  CONSTRAINT `UserAvatar_User_id_fk` FOREIGN KEY (`userId`) REFERENCES `User` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `UserToken`
--

DROP TABLE IF EXISTS `UserToken`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `UserToken` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `selector` char(12) NOT NULL,
  `hashedValidator` char(64) NOT NULL,
  `expires` datetime NOT NULL,
  `expired` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  CONSTRAINT `UserToken_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'quiz'
--
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP FUNCTION IF EXISTS `CreateCategory` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` FUNCTION `CreateCategory`(p_name TINYTEXT, p_description TINYTEXT) RETURNS int(11)
BEGIN
    DECLARE category_exists INT;

    -- Sprawdź, czy istnieje użytkownik o podanym pseudonimie
    SELECT COUNT(*) INTO category_exists
    FROM Category
    WHERE name = p_name;

    IF category_exists > 0 THEN
        RETURN 1;
    END IF;

    INSERT INTO Category (name, description)
    VALUES (p_name, p_description);

    RETURN 0;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `AddAnswerContent` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `AddAnswerContent`(
    IN p_answerId BIGINT,
    IN p_textContent TEXT,
    IN p_numericContent DECIMAL(10, 2)
)
BEGIN
    -- Insert the answer content
    INSERT INTO UserAnswerContent (userAnswerId, textContent, numericContent)
    VALUES (p_answerId, p_textContent, p_numericContent);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `AddQuizCategory` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `AddQuizCategory`(
    IN p_quizId INT,
    IN p_categoryId INT,
    IN p_userId INT
)
BEGIN
    DECLARE v_actionTypeId INT;

    INSERT INTO QuizCategory (quizId, categoryId)
    VALUES (p_quizId, p_categoryId);

    SET v_actionTypeId = (SELECT id FROM LogActionType WHERE actionName = 'quizAssignedCategory');

    INSERT INTO LogUserAction (userId, actionId)
        VALUES (p_userId, v_actionTypeId);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `calculate_attempt_score` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `calculate_attempt_score`(IN p_attemptId INT)
BEGIN
    DECLARE total_score INT DEFAULT 0; -- Zmienna na wynik końcowy

    -- 1. Liczenie punktów dla pytań typu `textareaAnswers`
    SELECT 
        total_score + COUNT(DISTINCT ua.id) INTO total_score
    FROM 
        UserAnswer ua
        INNER JOIN UserAnswerContent uac ON ua.id = uac.userAnswerId
        INNER JOIN QuestionAnswers qa ON qa.questionId = ua.questionId
    WHERE 
        ua.attemptId = p_attemptId
        AND qa.textContent = uac.textContent
        AND qa.isValid = 1
        AND ua.questionId IN (SELECT id FROM Question WHERE type = 'textareaAnswers');

    -- 2. Liczenie punktów dla pytań typu `multipleChoice`
    SELECT 
        total_score + COUNT(DISTINCT outer_question.id) INTO total_score
    FROM 
        Question outer_question
    WHERE 
        outer_question.id IN (
            SELECT ua.questionId
            FROM UserAnswer ua
            INNER JOIN UserAnswerContent uac ON ua.id = uac.userAnswerId
            INNER JOIN QuestionContent qc ON qc.questionId = ua.questionId
            WHERE 
                ua.attemptId = p_attemptId
            GROUP BY ua.questionId
            HAVING COUNT(DISTINCT uac.textContent) = (
                SELECT COUNT(DISTINCT qc2.textContent)
                FROM QuestionContent qc2
                WHERE qc2.questionId = ua.questionId
            )
        );

    -- Zwrócenie wyniku końcowego
    SELECT total_score AS final_score;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CheckRememberMe` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `CheckRememberMe`(IN p_selector varchar(255))
BEGIN
    SELECT * FROM UserToken
    WHERE selector = p_selector
      AND expires >= NOW()
      AND expired IS NULL;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CreateUser` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `CreateUser`(IN p_name tinytext, IN p_email tinytext,
                                                       IN p_passwordHash tinytext, OUT resultCode int)
proc_block: BEGIN
    DECLARE user_exists INT DEFAULT 0;
    DECLARE email_exists INT DEFAULT 0;
    DECLARE registerActionId INT;
    DECLARE newUserId INT;

    SELECT id INTO registerActionId FROM LogActionType WHERE actionName = 'userRegister';

    SELECT COUNT(*) INTO user_exists FROM User WHERE name = p_name;
    IF user_exists > 0 THEN
        INSERT INTO LogUserAction (actionId, detail)
        VALUES (registerActionId, CONCAT('Username exists: ', p_name));
        SET resultCode = 1; -- username taken
        LEAVE proc_block;
    END IF;

    SELECT COUNT(*) INTO email_exists FROM User WHERE email = p_email;
    IF email_exists > 0 THEN
        INSERT INTO LogUserAction (actionId, detail)
        VALUES (registerActionId, CONCAT('Email exists: ', p_email));
        SET resultCode = 2; -- email taken
        LEAVE proc_block;
    END IF;

    INSERT INTO User (name, email, passwordHash, type) VALUES (p_name, p_email, p_passwordHash, 'user');
    SET newUserId = LAST_INSERT_ID();

    INSERT INTO LogUserAction (userId, actionId) VALUES (newUserId, registerActionId);

    SET resultCode = 0; -- success
END proc_block ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `DeleteQuestion` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `DeleteQuestion`(IN p_questionId int, IN p_userId int)
BEGIN
    DECLARE v_actionTypeId INT;
    DECLARE v_quizId INT;

    SET v_quizId = (SELECT quizId from Question WHERE id = p_questionId);

    DELETE FROM Question WHERE id = p_questionId;

    SET v_actionTypeId = (SELECT id FROM LogActionType WHERE actionName = 'questionDeleted');

    INSERT INTO LogUserAction (userId, actionId, detail) VALUES (p_userId, v_actionTypeId, v_quizId);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `DeleteQuiz` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `DeleteQuiz`(
    IN p_quizId INT,
    IN p_userId INT
)
BEGIN
    DECLARE v_actionTypeId INT;
    DECLARE v_quizName tinytext;

    SET v_quizName = (SELECT name FROM Quiz WHERE id = p_quizId);

    DELETE FROM Quiz WHERE id = p_quizId;

    SET v_actionTypeId = (SELECT id FROM LogActionType WHERE actionName = 'quizDeleted');

    INSERT INTO LogUserAction (userId, actionId, detail) VALUES (p_userId, v_actionTypeId, v_quizName);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `EndAttempt` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `EndAttempt`(
    IN p_attemptId INT
)
BEGIN
    UPDATE QuizAttempt
    SET ended     = now()
    WHERE id = p_attemptId;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ForgetMe` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `ForgetMe`(IN p_userId INT, IN p_selector VARCHAR(255))
BEGIN
    UPDATE UserToken SET expires = NOW()
    WHERE userId = p_userId AND selector = p_selector;

    INSERT INTO LogUserAction (userId, actionId)
    SELECT p_userId, id FROM LogActionType WHERE actionName = 'userTokenExpired';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `LogLoginAttempt` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `LogLoginAttempt`(IN p_userId int, IN p_detail text)
BEGIN
    DECLARE loginActionId INT;

    SELECT id INTO loginActionId FROM LogActionType WHERE actionName = 'userLogin';

    IF p_userId IS NULL THEN
        INSERT INTO LogUserAction (actionId, detail)
        VALUES (loginActionId, p_detail);
    ELSE
        INSERT INTO LogUserAction (userId, actionId, detail)
        VALUES (p_userId, loginActionId, p_detail);
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `RememberUser` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `RememberUser`(IN p_userId INT, IN p_selector VARCHAR(255), IN p_hashedValidator VARCHAR(255), IN p_expires DATETIME)
BEGIN
    INSERT INTO UserToken (userId, selector, hashedValidator, expires)
    VALUES (p_userId, p_selector, p_hashedValidator, p_expires);

    INSERT INTO LogUserAction (userId, actionId)
    SELECT p_userId, id FROM LogActionType WHERE actionName = 'userTokenCreated';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `RemoveQuizCategory` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `RemoveQuizCategory`(
    IN p_quizId INT,
    IN p_categoryId INT,
    IN p_userId INT
)
BEGIN
    DECLARE v_actionTypeId INT;

    DELETE FROM QuizCategory WHERE quizId = p_quizId AND categoryId = p_categoryId;

    SET v_actionTypeId = (SELECT id FROM LogActionType WHERE actionName = 'quizRemovedCategory');

    INSERT INTO LogUserAction (userId, actionId) VALUES (p_userId, v_actionTypeId);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `SetCategory` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `SetCategory`(IN p_userId INT, IN p_categoryId INT, IN p_name VARCHAR(255), IN p_description TEXT)
BEGIN
    DECLARE affected_rows INT;

    UPDATE Category SET name = p_name, description = p_description WHERE id = p_categoryId;
    SET affected_rows = ROW_COUNT();

    IF affected_rows > 0 THEN
        INSERT INTO LogUserAction (userId, actionId, detail)
        SELECT p_userId, id, CONCAT('Category with id ', p_categoryId,' updated.')
        FROM LogActionType WHERE actionName = 'categoryEdit';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `SetQuestion` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `SetQuestion`(IN p_questionId int, IN p_quizId INT, IN p_type enum('guessFromImage', 'textareaAnswers', 'singleChoice', 'multipleChoice', 'fillTheGaps'), IN p_userId INT)
BEGIN
    DECLARE v_questionId INT;
    DECLARE v_actionTypeId INT;

    IF p_questionId IS NULL THEN
        -- Insert new question
        INSERT INTO Question (quizId, type)
        VALUES (p_quizId, p_type);

        SET v_questionId = LAST_INSERT_ID();
        SET v_actionTypeId = (SELECT id FROM LogActionType WHERE actionName = 'questionCreated');
    ELSE
        -- Update existing question
        UPDATE Question
        SET type       = p_type
        WHERE id = p_questionId;

        SET v_questionId = p_questionId;
        SET v_actionTypeId = (SELECT id FROM LogActionType WHERE actionName = 'questionUpdated');
    END IF;

    INSERT INTO LogUserAction (userId, actionId, detail) VALUE (p_userId, v_actionTypeId, v_questionId);

    -- Return the question ID
    SELECT v_questionId as questionId;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `SetQuestionAnswer` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `SetQuestionAnswer`(IN p_answerId int, IN p_questionId int,
                                                              IN p_textContent text, IN p_imageSrc tinytext,
                                                              IN p_videoSrc tinytext, IN p_isValid tinyint(1),
                                                              IN p_userId int)
BEGIN
    DECLARE v_actionTypeId INT;

    IF p_answerId IS NULL THEN
        -- Wstaw nową odpowiedź
        INSERT INTO QuestionAnswers (questionId, textContent, imageSrc, videoSrc, isValid)
        VALUES (p_questionId, p_textContent, p_imageSrc, p_videoSrc, p_isValid);

        SET p_answerId = LAST_INSERT_ID();
        SET v_actionTypeId = (SELECT id FROM LogActionType WHERE actionName = 'questionAnswerCreated');
    ELSE
        -- Aktualizuj istniejącą odpowiedź
        UPDATE QuestionAnswers
        SET textContent = p_textContent,
            videoSrc = p_videoSrc,
            imageSrc = p_imageSrc,
            isValid = p_isValid
        WHERE id = p_answerId;

        SET v_actionTypeId = (SELECT id FROM LogActionType WHERE actionName = 'questionAnswerUpdated');
    END IF;

    -- Zarejestruj akcję użytkownika
    INSERT INTO LogUserAction (userId, actionId, detail)
    VALUES (p_userId, v_actionTypeId, p_answerId);

    -- Zwróć ID odpowiedzi
    SELECT p_answerId AS answerId;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `SetQuestionContent` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `SetQuestionContent`(IN p_contentId int, IN p_questionId int,
                                                               IN p_textContent text, IN p_imageSrc tinytext,
                                                               IN p_videoSrc tinytext, IN p_userId int)
BEGIN
    DECLARE v_actionTypeId INT;

    IF p_contentId IS NULL THEN
        INSERT INTO QuestionContent (questionId, textContent, imageSrc, videoSrc)
        VALUES (p_questionId, p_textContent, p_imageSrc, p_videoSrc);

        SET p_contentId = LAST_INSERT_ID();
        SET v_actionTypeId = (SELECT id FROM LogActionType WHERE actionName = 'questionContentCreated');
    ELSE
        UPDATE QuestionContent
        SET textContent = p_textContent,
            imageSrc = p_imageSrc,
            videoSrc = p_videoSrc
        WHERE id = p_contentId;

        SET v_actionTypeId = (SELECT id FROM LogActionType WHERE actionName = 'questionContentUpdated');
    END IF;

    INSERT INTO LogUserAction (userId, actionId, detail)
    VALUES (p_userId, v_actionTypeId, p_questionId);

    SELECT p_contentId AS contentId;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `SetQuiz` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `SetQuiz`(IN p_quizId int, IN p_name varchar(255), IN p_description text,
                                                    IN p_userId int)
BEGIN
    DECLARE v_quizId INT;
    DECLARE v_actionTypeId INT;

    IF p_quizId IS NULL THEN
        -- Insert new quiz
        INSERT INTO Quiz (name, description, ownerId)
        VALUES (p_name, p_description, p_userId);

        SET v_quizId = LAST_INSERT_ID();
        SET v_actionTypeId = (SELECT id FROM LogActionType WHERE actionName = 'quizCreated');
    ELSE
        -- Update existing quiz
        UPDATE Quiz
        SET name       = p_name,
            description = p_description
        WHERE id = p_quizId;

        SET v_quizId = p_quizId;
        SET v_actionTypeId = (SELECT id FROM LogActionType WHERE actionName = 'quizUpdated');
    END IF;
    
    INSERT INTO LogUserAction (userId, actionId, detail) VALUE (p_userId, v_actionTypeId, v_quizId);

    -- Return the quiz ID
    SELECT v_quizId as quizId;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `StartAttempt` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`rychellos`@`localhost` PROCEDURE `StartAttempt`(IN p_quizId int, IN p_userId int)
BEGIN
    DECLARE lastAttemptId INT; -- Deklaracja zmiennej

    -- Close all active attempts except one
    UPDATE QuizAttempt
    SET ended = CURRENT_TIMESTAMP
    WHERE userId = p_userId
      AND quizId = p_quizId
      AND ended IS NULL
      AND NOT EXISTS (
          SELECT 1
          FROM QuizAttempt AS QA
          WHERE QA.userId = p_userId
            AND QA.quizId = p_quizId
            AND QA.ended IS NULL
            AND QA.id <> QuizAttempt.id
      );

    -- Start new attempt if no active attempt exists
    IF (SELECT COUNT(*) 
        FROM QuizAttempt 
        WHERE userId = p_userId 
          AND quizId = p_quizId 
          AND ended IS NULL) = 0 THEN

        INSERT INTO QuizAttempt (userId, quizId, started)
        VALUES (p_userId, p_quizId, CURRENT_TIMESTAMP);
   
   -- Pobierz poprawne attemptId
   SET lastAttemptId = LAST_INSERT_ID();
   
   -- Teraz użyj lastAttemptId do wstawienia odpowiedzi
   INSERT INTO UserAnswer (attemptId, userId, questionId)
   SELECT lastAttemptId, p_userId, Question.id
   FROM Question
   WHERE Question.quizId = p_quizId;
    END IF;

    -- Ensure missing answers are pre-generated
    INSERT INTO UserAnswer (attemptId, userId, questionId)
    SELECT QuizAttempt.id,
           QuizAttempt.userId,
           Question.id
    FROM QuizAttempt
    JOIN Question 
        ON QuizAttempt.quizId = Question.quizId
    LEFT JOIN UserAnswer 
        ON UserAnswer.attemptId = QuizAttempt.id
        AND UserAnswer.questionId = Question.id
    WHERE QuizAttempt.quizId = p_quizId
      AND QuizAttempt.userId = p_userId
      AND QuizAttempt.ended IS NULL
      AND UserAnswer.id IS NULL;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-06-17  2:02:11
