create table if not exists class(
    classname varchar(10), 
    primary key (classname)
);

create table if not exists students(
    uid varchar(15), 
    name varchar(40), 
    email varchar(40), 
    classname varchar(20), 
    primary key(uid), 
    foreign key(classname) references class(classname)
);

create table if not exists teachers(
    id varchar(15), 
    name varchar(40), 
    email varchar(40), 
    password varchar(40), 
    primary key(id)
);

CREATE TABLE if not exists courses (
    course_id varchar(15), -- primary key, idk man
    name varchar(10) unique NOT NULL,
    teacher_id varchar(15) NOT NULL,
    classname varchar(10) NOT NULL,
    archived boolean default false NOT NULL,
    created_at timestamp default current_timestamp NOT NULL,
    foreign key(classname) references class(classname),
    foreign key(teacher_id) references teachers(id)
);

CREATE TABLE if not exists attendance (
    attendance_id varchar(15) primary key,
    classname varchar(10),
    student_uid varchar(15),
    course_id varchar(15),
    -- status ENUM('p', 'a') NOT NULL,
    status boolean NOT NULL,
    attendance_date date,
    attendance_time time,
    foreign key(classname) references class(classname) ON DELETE CASCADE,
    foreign key(student_uid) references students(uid) ON DELETE CASCADE,
    foreign key(course_id) references courses(course_id) ON DELETE CASCADE
);


INSERT INTO class (classname) VALUES ('TE-AIML');

INSERT INTO students (uid, name, email, classname) 
VALUES 
('2022600001', 'John Doe', 'john.doe@example.com', 'TE-AIML'),
('2022600002', 'Jane Smith', 'jane.smith@example.com', 'TE-AIML'),
('2022600003', 'Alice Johnson', 'alice.johnson@example.com', 'TE-AIML');

INSERT INTO teachers (id, name, email, password) 
VALUES 
('T2023A001', 'Prof. Brown', 'brownt@example.com', 'pass1'),
('T2023A002', 'Prof. Green', 'greent@example.com', 'pass2');

INSERT INTO courses (course_id, name, teacher_id, classname, created_at) 
VALUES 
('C2023DB01', 'DBMS', 'T2023A001', 'TE-AIML', CURRENT_TIMESTAMP),
('C2023CN01', 'CN', 'T2023A002', 'TE-AIML', CURRENT_TIMESTAMP);

INSERT INTO attendance (attendance_id, classname, student_uid, course_id, status, attendance_date, attendance_time) 
VALUES 
('A2023DB001', 'TE-AIML', '2022600001', 'C2023DB01', 'p', '2024-10-05', '09:00:00'),
('A2023DB002', 'TE-AIML', '2022600002', 'C2023DB01', 'a', '2024-10-05', '09:00:00'),
('A2023DB003', 'TE-AIML', '2022600003', 'C2023DB01', 'p', '2024-10-05', '09:00:00'),
('A2023CN001', 'TE-AIML', '2022600001', 'C2023CN01', 'p', '2024-10-05', '10:00:00'),
('A2023CN002', 'TE-AIML', '2022600002', 'C2023CN01', 'p', '2024-10-05', '10:00:00'),
('A2023CN003', 'TE-AIML', '2022600003', 'C2023CN01', 'a', '2024-10-05', '10:00:00');


-- ALTER TABLE students ADD COLUMN student_type ENUM('Regular', 'DSE') GENERATED ALWAYS AS (
    -- CASE
        -- WHEN SUBSTRING(uid, 1, 4) = CAST(YEAR(CURDATE()) AS CHAR) THEN 'Regular'
        -- WHEN SUBSTRING(uid, 1, 4) = CAST(YEAR(CURDATE()) - 1 AS CHAR) THEN 'DSE'
        -- ELSE NULL
    -- END
-- ) STORED;


-- DELIMITER //
-- CREATE PROCEDURE create_course_attendance_table(IN p_course_id VARCHAR(15), IN p_academic_year VARCHAR(4))
-- BEGIN
--     SET @table_name = CONCAT(p_course_id, '_', p_academic_year);
--     SET @create_table_sql = CONCAT('
--     CREATE TABLE IF NOT EXISTS ', @table_name, ' (
--         student_uid VARCHAR(15),
--         attendance_date DATE,
--         status BOOLEAN NOT NULL,
--         PRIMARY KEY (student_uid, attendance_date),
--         FOREIGN KEY (student_uid) REFERENCES students(uid)
--     )');
--     PREPARE stmt FROM @create_table_sql;
--     EXECUTE stmt;
--     DEALLOCATE PREPARE stmt;
-- END //
-- DELIMITER ;
