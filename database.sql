CREATE TABLE `UserAvatar` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `url` tinytext
);

CREATE TABLE `User` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `email` tinytext,
  `hash` tinytext,
  `avatarId` int,
  `type` ENUM ('administrator', 'moderator', 'user')
);

CREATE TABLE `QuizCategory` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` tinytext,
  `description` text
);

CREATE TABLE `Quiz` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` tinytext,
  `description` text,
  `categoryId` int NOT NULL,
  `owner` int NOT NULL
);

CREATE TABLE `QuestionContent` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `questionId` int NOT NULL,
  `textContent` text,
  `imageSrc` tinytext,
  `videoSrc` tinytext
);

CREATE TABLE `QuestionAnswer` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `questionId` int NOT NULL,
  `textContent` text,
  `imageSrc` tinytext,
  `videoSrc` tinytext,
  `isValid` bool
);

CREATE TABLE `Question` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `quizId` int NOT NULL,
  `type` ENUM ('guessFromImage', 'textareaAnswers', 'singleChoice', 'multipleChioce')
);

CREATE TABLE `QuizAttempt` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `quizId` int NOT NULL,
  `when` DateTime NOT NULL
);

CREATE TABLE `UserAnswer` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `attemptId` int NOT NULL,
  `userId` int NOT NULL,
  `questionId` int NOT NULL
);

CREATE TABLE `UserAnswerContent` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `userAnswerId` int NOT NULL,
  `textContent` text,
  `numericContent` int
);

CREATE TABLE `LogActionType` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `actionName` tinytext NOT NULL
);

CREATE TABLE `LogUserAction` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `userId` int NOT NULL,
  `action` int NOT NULL,
  `when` datetime
);

ALTER TABLE `User` ADD FOREIGN KEY (`avatarId`) REFERENCES `UserAvatar` (`id`);

ALTER TABLE `Quiz` ADD FOREIGN KEY (`categoryId`) REFERENCES `QuizCategory` (`id`);

ALTER TABLE `Quiz` ADD FOREIGN KEY (`owner`) REFERENCES `User` (`id`);

ALTER TABLE `QuestionContent` ADD FOREIGN KEY (`questionId`) REFERENCES `Question` (`id`);

ALTER TABLE `QuestionAnswer` ADD FOREIGN KEY (`questionId`) REFERENCES `Question` (`id`);

ALTER TABLE `Question` ADD FOREIGN KEY (`quizId`) REFERENCES `Quiz` (`id`);

ALTER TABLE `QuizAttempt` ADD FOREIGN KEY (`quizId`) REFERENCES `Quiz` (`id`);

ALTER TABLE `UserAnswer` ADD FOREIGN KEY (`attemptId`) REFERENCES `QuizAttempt` (`id`);

ALTER TABLE `UserAnswer` ADD FOREIGN KEY (`userId`) REFERENCES `User` (`id`);

ALTER TABLE `UserAnswer` ADD FOREIGN KEY (`questionId`) REFERENCES `Question` (`id`);

ALTER TABLE `UserAnswerContent` ADD FOREIGN KEY (`userAnswerId`) REFERENCES `UserAnswer` (`id`);

ALTER TABLE `LogUserAction` ADD FOREIGN KEY (`userId`) REFERENCES `User` (`id`);

ALTER TABLE `LogUserAction` ADD FOREIGN KEY (`action`) REFERENCES `LogActionType` (`id`);
