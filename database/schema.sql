-- DaoHanTang Chinese Learning Platform
-- Database Schema

CREATE DATABASE IF NOT EXISTS daohantang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE daohantang;

-- Users
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Courses
CREATE TABLE courses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    thumbnail VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Course Enrollments (access gatekeeping)
CREATE TABLE course_enrollments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    course_id INT UNSIGNED NOT NULL,
    status ENUM('pending', 'approved', 'declined') NOT NULL DEFAULT 'pending',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_enrollment (user_id, course_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Lessons (Intro = 0, Lessons 1-15)
CREATE TABLE lessons (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    lesson_number TINYINT UNSIGNED NOT NULL COMMENT '0=Intro, 1-15=Lessons',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_lesson_order (course_id, lesson_number),
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Lesson Items (video-quiz pairs, 2-5 per lesson)
CREATE TABLE lesson_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lesson_id INT UNSIGNED NOT NULL,
    item_order TINYINT UNSIGNED NOT NULL DEFAULT 1,
    video_url VARCHAR(500) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_item_order (lesson_id, item_order),
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Quizzes (multiple questions per lesson item)
CREATE TABLE quizzes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lesson_item_id INT UNSIGNED NOT NULL,
    question TEXT NOT NULL,
    option_a VARCHAR(500) NOT NULL,
    option_b VARCHAR(500) NOT NULL,
    option_c VARCHAR(500) NOT NULL,
    option_d VARCHAR(500) NOT NULL,
    correct_option ENUM('a', 'b', 'c', 'd') NOT NULL,
    FOREIGN KEY (lesson_item_id) REFERENCES lesson_items(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- User Progress per lesson
CREATE TABLE user_progress (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    lesson_id INT UNSIGNED NOT NULL,
    completed_items_count TINYINT UNSIGNED NOT NULL DEFAULT 0,
    is_lesson_completed TINYINT(1) NOT NULL DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_lesson (user_id, lesson_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Track completed individual lesson items (for step progression within a lesson)
CREATE TABLE user_item_progress (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    lesson_item_id INT UNSIGNED NOT NULL,
    quiz_passed TINYINT(1) NOT NULL DEFAULT 0,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_item (user_id, lesson_item_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_item_id) REFERENCES lesson_items(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Default admin (password: admin123)
INSERT INTO users (name, email, password, role) VALUES
('Administrator', 'admin@daohantang.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Sample course
INSERT INTO courses (title, description, price, thumbnail) VALUES
('HSK Level 1', 'Complete HSK 1 preparation course with 15 structured lessons covering pinyin, basic vocabulary, and grammar.', 50000, 'assets/images/course-hsk1.jpg'),
('HSK Level 2', 'Advance your Chinese with HSK 2 curriculum. Build on fundamentals with expanded vocabulary and sentence patterns.', 65000, 'assets/images/course-hsk2.jpg');
