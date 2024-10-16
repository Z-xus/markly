use markly_db;
-- Existing tables remain the same

CREATE TABLE IF NOT EXISTS department (
    dept_code VARCHAR(10) PRIMARY KEY  -- AIML, DS, COMPSA, etc.
);

CREATE TABLE IF NOT EXISTS year (
    name VARCHAR(2) PRIMARY KEY  -- BE, TE, SE, FE, etc.
);

CREATE TABLE IF NOT EXISTS class (
    classname VARCHAR(10) PRIMARY KEY,  -- TE-AIML, SE-DS, etc.
    dept VARCHAR(10),
    year VARCHAR(2),
    FOREIGN KEY (dept) REFERENCES department(dept_code),
    FOREIGN KEY (year) REFERENCES year(name),
    UNIQUE (dept, year),
    CHECK (classname = CONCAT(year, '-', dept))  -- Enforce format: <year>-<dept_code>
);

INSERT INTO department (dept_code) VALUES ('AIML'), ('DS'), ('COMPSA'), ('COMPSB'), ('EXTC');
INSERT INTO year (name) VALUES ('FE'), ('SE'), ('TE'), ('BE');

-- <year>-<dept_code>
INSERT INTO class (classname, dept, year)
SELECT CONCAT(y.name, '-', d.dept_code) AS classname, d.dept_code, y.name
FROM department d
CROSS JOIN year y;

CREATE TABLE IF NOT EXISTS students (
    uid VARCHAR(15),
    f_name VARCHAR(30) NOT NULL,
    m_name VARCHAR(30) NOT NULL,
    l_name VARCHAR(30) NOT NULL,
    email VARCHAR(40),
    classname VARCHAR(20) NOT NULL,
    PRIMARY KEY (uid),
    FOREIGN KEY (classname) REFERENCES class(classname)
);

DELIMITER $$
CREATE TRIGGER before_insert_students
BEFORE INSERT ON students
FOR EACH ROW
BEGIN
    DECLARE admission_year CHAR(2);
    SET admission_year = SUBSTRING(NEW.uid, 3, 2);  -- Extracts characters at position 3 and 4 (e.g., '23' from '2023601001')
    SET NEW.email = CONCAT(LOWER(NEW.f_name), '.', LOWER(NEW.l_name), admission_year, '@spit.ac.in');
END $$
DELIMITER ;

INSERT INTO students (uid, f_name, m_name, l_name, classname) VALUES
    ('2023601001', 'John', 'A.', 'Doe', 'TE-AIML'),
    ('2023600001', 'Jane', 'B.', 'Smith', 'TE-AIML'),
    ('2023600002', 'Alice', 'C.', 'Johnson', 'TE-AIML'),
    ('2022600003', 'Bob', 'D.', 'Brown', 'SE-DS'),
    ('2021600004', 'Charlie', 'E.', 'Davis', 'TE-COMPSA');

CREATE TABLE IF NOT EXISTS teachers (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(40),
    email VARCHAR(40),
    password VARCHAR(40)
);

INSERT INTO teachers (id, name, email, password) VALUES
    (UUID(), 'Prof. Brown', 'brownt@example.com', 'pass1'),
    (UUID(), 'Dr. Kalbande', 'kalbande@example.com', 'pass3'),
    (UUID(), 'Prof. Green', 'greent@example.com', 'pass2');

CREATE TABLE IF NOT EXISTS courses (
    course_id VARCHAR(80), -- DBMS_2324_TE-AIML
    name VARCHAR(10) UNIQUE NOT NULL, -- DBMS, OOP, etc.
    teacher_id CHAR(36) NOT NULL,
    classname VARCHAR(10) NOT NULL,
    academic_year VARCHAR(4) NOT NULL, -- 2324, 2425, etc.
    archived BOOLEAN DEFAULT FALSE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (course_id),
    FOREIGN KEY (classname) REFERENCES class(classname),
    FOREIGN KEY (teacher_id) REFERENCES teachers(id)
);

INSERT INTO courses (course_id, name, teacher_id, classname, academic_year) VALUES
    (CONCAT('DBMS', '_', '2324', '_', 'TE_AIML'), 'DBMS', (SELECT id FROM teachers WHERE name = 'Prof. Brown'), 'TE-AIML', '2324'),
    (CONCAT('NNFL', '_', '2324', '_', 'TE_AIML'), 'NNFL', (SELECT id FROM teachers WHERE name = 'Dr. Kalbande'), 'TE-AIML', '2324'),
    (CONCAT('OOP', '_', '2324', '_', 'TE_AIML'), 'OOP', (SELECT id FROM teachers WHERE name = 'Prof. Green'), 'TE-AIML', '2324');

-- Function to create a new course-specific attendance table

DELIMITER //
CREATE PROCEDURE create_course_attendance_table(
    IN p_course_id VARCHAR(80)
)
BEGIN
    SET @table_name = p_course_id;
    SET @create_table_sql = CONCAT('
    CREATE TABLE IF NOT EXISTS ', @table_name, ' (
        student_uid VARCHAR(15),
        attendance_date DATE NOT NULL,
        attendance_time TIME NOT NULL,
        status BOOLEAN NOT NULL,
        double_entry BOOLEAN NOT NULL DEFAULT FALSE,
        PRIMARY KEY (student_uid, attendance_date),
        FOREIGN KEY (student_uid) REFERENCES students(uid)
    )');
    PREPARE stmt FROM @create_table_sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //
DELIMITER ;

-- CALL create_course_attendance_table('DBMS_2324_TE_AIML');
