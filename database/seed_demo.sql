-- Demo seed data for HSK Level 1 course
USE daohantang;

-- Intro + first 3 lessons for course ID 1
INSERT INTO lessons (course_id, title, lesson_number) VALUES
(1, 'Welcome & Course Overview', 0),
(1, 'Pinyin Basics', 1),
(1, 'Greetings & Introductions', 2),
(1, 'Numbers 1-10', 3);

-- Intro lesson items (2 video-quiz pairs)
SET @intro_id = (SELECT id FROM lessons WHERE course_id = 1 AND lesson_number = 0 LIMIT 1);

INSERT INTO lesson_items (lesson_id, item_order, video_url) VALUES
(@intro_id, 1, 'https://www.youtube.com/watch?v=1I9Zr30bG4E'),
(@intro_id, 2, 'https://www.youtube.com/watch?v=1I9Zr30bG4E');

SET @item1 = (SELECT id FROM lesson_items WHERE lesson_id = @intro_id AND item_order = 1 LIMIT 1);
SET @item2 = (SELECT id FROM lesson_items WHERE lesson_id = @intro_id AND item_order = 2 LIMIT 1);

INSERT INTO quizzes (lesson_item_id, question, option_a, option_b, option_c, option_d, correct_option) VALUES
(@item1, 'What does 你好 (nǐ hǎo) mean?', 'Goodbye', 'Hello', 'Thank you', 'Sorry', 'b'),
(@item1, 'Which tone mark is on "nǐ"?', 'First tone', 'Second tone', 'Third tone', 'Fourth tone', 'c'),
(@item1, 'How do you say "thank you" in Chinese?', '对不起', '再见', '谢谢', '你好', 'c'),
(@item1, 'Pinyin is used to represent:', 'Chinese characters only', 'Chinese pronunciation', 'Japanese sounds', 'Korean grammar', 'b'),
(@item1, 'HSK Level 1 covers approximately how many words?', '50', '150', '600', '5000', 'b'),

(@item2, 'The first tone in Mandarin is:', 'Rising', 'Flat/high', 'Falling-rising', 'Falling', 'b'),
(@item2, 'Which is the correct pinyin for 中国?', 'zhōng guó', 'zhong guo', 'zhǒng guǒ', 'zōng guó', 'a'),
(@item2, 'What character means "person"?', '大', '人', '口', '手', 'b'),
(@item2, 'How many tones does standard Mandarin have?', '3', '4', '5', '6', 'c'),
(@item2, 'Which greeting is most formal?', '嗨', '你好', '您好', '喂', 'c');

-- Lesson 1 items
SET @lesson1_id = (SELECT id FROM lessons WHERE course_id = 1 AND lesson_number = 1 LIMIT 1);

INSERT INTO lesson_items (lesson_id, item_order, video_url) VALUES
(@lesson1_id, 1, 'https://www.youtube.com/watch?v=1I9Zr30bG4E'),
(@lesson1_id, 2, 'https://www.youtube.com/watch?v=1I9Zr30bG4E');

SET @l1item1 = (SELECT id FROM lesson_items WHERE lesson_id = @lesson1_id AND item_order = 1 LIMIT 1);

INSERT INTO quizzes (lesson_item_id, question, option_a, option_b, option_c, option_d, correct_option) VALUES
(@l1item1, 'Pinyin "a" with first tone is written as:', 'ā', 'á', 'ǎ', 'à', 'a'),
(@l1item1, 'Which initial consonant is in "bàba"?', 'p', 'b', 'm', 'f', 'b'),
(@l1item1, 'The word "māma" means:', 'Father', 'Mother', 'Sister', 'Brother', 'b'),
(@l1item1, 'Which is a final (vowel) in pinyin?', 'b', 'zh', 'ao', 'sh', 'c'),
(@l1item1, 'Third tone is described as:', 'High and level', 'Rising', 'Dipping/falling-rising', 'Falling', 'c');
