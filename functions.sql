DELIMITER $$

CREATE FUNCTION CreateQuiz(p_title TINYTEXT, p_categoryId INT, p_owner INT)
    RETURN INT
BEGIN
    insert into Quiz (title, categoryId, owner) value (p_title, p_categoryId, p_owner);
    return LAST_INSERT_ID();
END $$


CREATE FUNCTION AddQuizQuestion(p_quizId int, p_type enum ('guess_from_image', 'textarea_answers', 'single_choice', 'multiple_choice'))
    RETURN INT
BEGIN
    insert into Question (quizId, type) value (p_quizId, p_type);
    return LAST_INSERT_ID();
END $$


CREATE FUNCTION AddQuizQuestionContents(p_questionId int, p_textContent text, p_imageSrc text, p_videoSrc text)
    RETURN INT
BEGIN
    INSERT INTO QuestionContent (
         questionId,
         textContent,
         imageSrc,
         videoSrc
    ) value (
         p_questionId,
         p_textContent,
         p_imageSrc,
         p_videoSrc
    );
    RETURN LAST_INSERT_ID();
END $$


CREATE FUNCTION AddQuizQuestionAnswer(p_quizQuestionId int, p_textContent text, p_imageSrc text, p_videoSrc text)
    RETURN INT
BEGIN
    INSERT INTO QuestionContent (
         questionId,
         textContent,
         imageSrc,
         videoSrc
    ) value (
         p_quizQuestionId,
         p_textContent,
         p_imageSrc,
         p_videoSrc
    );
    RETURN LAST_INSERT_ID();
END $$

DELIMITER ;