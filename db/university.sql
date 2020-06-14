-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2020 at 11:07 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: new_university
--
CREATE DATABASE IF NOT EXISTS new_university DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE new_university;

-- --------------------------------------------------------

--
-- Table structure for table buildingandroom
--

-- DROP TABLE IF EXISTS buildingandroom;
CREATE TABLE IF NOT EXISTS buildingandroom (
  buildingName varchar(50) NOT NULL,
  roomNumber int(11) NOT NULL,
  capacity int(4) DEFAULT NULL,
  PRIMARY KEY (buildingName,roomNumber)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table buildingandroom
--

INSERT INTO buildingandroom (buildingName, roomNumber, capacity) VALUES
('amf', 311, 1),
('dk', 101, 30),
('dk', 102, 30),
('dk', 201, 40),
('dk', 202, 40),
('dk', 203, 30),
('dk', 501, 60),
('dmf', 101, 30),
('dmf', 102, 40),
('dmf', 103, 45),
('dmf', 213, 60),
('dmf', 311, 60),
('dmf', 411, 60),
('lmf', 308, 40),
('lmf', 319, 40),
('lmf', 321, 40),
('maslak', 300, 1);

-- --------------------------------------------------------

--
-- Table structure for table course
--

-- DROP TABLE IF EXISTS course;
CREATE TABLE IF NOT EXISTS course (
  courseCode varchar(10) NOT NULL,
  courseName varchar(100) NOT NULL,
  ects int(2) NOT NULL,
  numHours int(2) NOT NULL,
  prereq_courseCode varchar(10) DEFAULT NULL,
  PRIMARY KEY (courseCode),
  KEY fk_course_course1_idx (prereq_courseCode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table course
--

INSERT INTO course (courseCode, courseName, ects, numHours, prereq_courseCode) VALUES
('comp1111', 'Java Programming', 6, 3, NULL),
('comp1112', 'object oriented', 6, 2, 'comp1111'),
('comp2102', 'data structures', 6, 3, 'comp1112'),
('comp2222', 'database', 7, 3, 'comp1112'),
('INDE2001', 'Operations Research I', 7, 3, NULL),
('INDE2002', 'Operations Research II', 7, 3, 'INDE2001'),
('INDE2156', 'Engineering Statistics', 6, 3, NULL),
('math2103', 'discrete math', 5, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table curricula
--

-- DROP TABLE IF EXISTS curricula;
CREATE TABLE IF NOT EXISTS curricula (
  currCode varchar(10) NOT NULL,
  gradOrUrad tinyint(4) NOT NULL,
  dName varchar(50) NOT NULL,
  PRIMARY KEY (currCode,dName),
  KEY fk_curricula_department1_idx (dName)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table curricula
--

INSERT INTO curricula (currCode, gradOrUrad, dName) VALUES
('arch0813', 0, 'Architecture'),
('comp', 0, 'Computer Engineering'),
('cse0813', 0, 'Computer Engineering'),
('eco0813', 0, 'Economics'),
('gors0813', 0, 'Visual arts'),
('ie0813', 0, 'Industrial Engineering'),
('msc0813', 1, 'Computer Engineering'),
('phd0813', 1, 'Computer Engineering');

-- --------------------------------------------------------

--
-- Table structure for table curriculacourses
--

-- DROP TABLE IF EXISTS curriculacourses;
CREATE TABLE IF NOT EXISTS curriculacourses (
  currCode varchar(10) NOT NULL,
  dName varchar(50) NOT NULL,
  courseCode varchar(10) NOT NULL,
  PRIMARY KEY (currCode,dName,courseCode),
  KEY fk_curricula_has_course_course1_idx (courseCode),
  KEY fk_curricula_has_course_curricula1_idx (currCode,dName)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table curriculacourses
--

INSERT INTO curriculacourses (currCode, dName, courseCode) VALUES
('comp', 'Computer Engineering', 'comp1111'),
('comp', 'Computer Engineering', 'comp1112'),
('comp', 'Computer Engineering', 'comp2102'),
('comp', 'Computer Engineering', 'comp2222'),
('comp', 'Computer Engineering', 'INDE2156'),
('comp', 'Computer Engineering', 'math2103'),
('ie0813', 'Industrial Engineering', 'INDE2001'),
('ie0813', 'Industrial Engineering', 'INDE2002'),
('ie0813', 'Industrial Engineering', 'INDE2156');

-- --------------------------------------------------------

--
-- Table structure for table department
--

-- DROP TABLE IF EXISTS department;
CREATE TABLE IF NOT EXISTS department (
  dName varchar(50) NOT NULL,
  budget double DEFAULT NULL,
  headSsn varchar(20) NOT NULL,
  buildingName varchar(50) NOT NULL,
  PRIMARY KEY (dName),
  KEY fk_department_instructor1_idx (headSsn),
  KEY fk_department_building1_idx (buildingName)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table department
--

INSERT INTO department (dName, budget, headSsn, buildingName) VALUES
('Architecture', NULL, 'i4', 'dk'),
('Computer Engineering', NULL, 'i1', 'amf'),
('Economics', NULL, 'i3', 'amf'),
('Industrial Engineering', NULL, 'i2', 'amf'),
('Visual arts', NULL, 'i5', 'maslak');

-- --------------------------------------------------------

--
-- Table structure for table emails
--

-- DROP TABLE IF EXISTS emails;
CREATE TABLE IF NOT EXISTS emails (
  email varchar(100) NOT NULL,
  sssn varchar(20) NOT NULL,
  PRIMARY KEY (email,sssn),
  KEY fk_emails_student1_idx (sssn)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table emails
--

INSERT INTO emails (email, sssn) VALUES
('mscomp1@isik.edu.tr', 's3'),
('mscomp2@isik.edu.tr', 's6'),
('st1@isik.edu.tr', 's1'),
('st2@isik.edu.tr', 's2'),
('st4@isik.edu.tr', 's4'),
('st5@isik.edu.tr', 's5'),
('student1@gmail.com', 's1');

-- --------------------------------------------------------

--
-- Table structure for table enrollment
--

-- DROP TABLE IF EXISTS enrollment;
CREATE TABLE IF NOT EXISTS enrollment (
  sssn varchar(20) NOT NULL,
  issn varchar(20) NOT NULL,
  courseCode varchar(10) NOT NULL,
  sectionId int(11) NOT NULL,
  yearr int(11) NOT NULL,
  semester int(11) NOT NULL,
  grade varchar(2) DEFAULT NULL,
  PRIMARY KEY (sssn,issn,courseCode,yearr,semester,sectionId),
  KEY fk_student_has_section_section1_idx (issn,courseCode,yearr,semester,sectionId),
  KEY fk_student_has_section_student1_idx (sssn)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table enrollment
--

INSERT INTO enrollment (sssn, issn, courseCode, sectionId, yearr, semester, grade) VALUES
('s1', 'i1', 'comp2222', 1, 2020, 2, NULL),
('s1', 'i6', 'comp1111', 1, 2020, 2, NULL),
('s1', 'i9', 'math2103', 1, 2020, 2, NULL),
('s2', 'i1', 'comp2222', 1, 2020, 2, NULL),
('s2', 'i6', 'comp1111', 2, 2020, 2, NULL),
('s2', 'i9', 'math2103', 1, 2020, 2, NULL),
('s4', 'i1', 'comp2222', 2, 2020, 2, NULL),
('s4', 'i6', 'comp1111', 1, 2020, 2, NULL),
('s4', 'i8', 'INDE2156', 1, 2020, 2, NULL),
('s4', 'i9', 'math2103', 1, 2020, 2, NULL),
('s5', 'i1', 'comp2222', 1, 2020, 2, NULL),
('s5', 'i9', 'INDE2156', 2, 2020, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table gradstudent
--

-- DROP TABLE IF EXISTS gradstudent;
CREATE TABLE IF NOT EXISTS gradstudent (
  ssn varchar(20) NOT NULL,
  supervisorSsn varchar(20) NOT NULL,
  PRIMARY KEY (ssn),
  KEY fk_GradStudent_instructor1_idx (supervisorSsn)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table gradstudent
--

INSERT INTO gradstudent (ssn, supervisorSsn) VALUES
('s3', 'i1'),
('s6', 'i6');

-- --------------------------------------------------------

--
-- Table structure for table instructor
--

-- DROP TABLE IF EXISTS instructor;
CREATE TABLE IF NOT EXISTS instructor (
  ssn varchar(20) NOT NULL,
  iname varchar(100) DEFAULT NULL,
  rankk varchar(45) DEFAULT NULL,
  baseSal double DEFAULT NULL,
  dName varchar(50) NOT NULL,
  extraSalary double DEFAULT NULL,
  PRIMARY KEY (ssn),
  KEY fk_instructor_department_idx (dName)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table instructor
--

INSERT INTO instructor (ssn, iname, rankk, baseSal, dName, extraSalary) VALUES
('i1', 'Louise Grant', 'AsstProf', 80000, 'Computer Engineering', NULL),
('i2', 'Ben King', 'Prof', 120000, 'Industrial Engineering', NULL),
('i3', 'Tracey Morales', 'AsstProf', 80000, 'Economics', NULL),
('i4', 'Harper Palmer', 'AsstProf', 80000, 'Architecture', NULL),
('i5', 'Rachel Jensen', 'AssocProf', 85000, 'Visual Arts', NULL),
('i6', 'Ralph Thomas', 'Prof', 120000, 'Computer Engineering', NULL),
('i7', 'instructor7', 'lecturer', 48000, 'Economics', NULL),
('i8', 'instructor8', 'AssocProf', 80000, 'Architecture', NULL),
('i9', 'Lois Silva', 'AsstProf', 80000, 'Industrial Engineering', NULL);

-- --------------------------------------------------------

--
-- Table structure for table prevdegree
--

-- DROP TABLE IF EXISTS prevdegree;
CREATE TABLE IF NOT EXISTS prevdegree (
  college varchar(100) NOT NULL,
  degree varchar(20) NOT NULL,
  yearr int(11) NOT NULL,
  Gradssn varchar(20) NOT NULL,
  PRIMARY KEY (college,degree,yearr,Gradssn),
  KEY fk_PrevDegree_GradStudent1_idx (Gradssn)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table prevdegree
--

INSERT INTO prevdegree (college, degree, yearr, Gradssn) VALUES
('Işık Un', 'computer Engineer ba', 2019, 's3'),
('Işık Un', 'computer Engineer ma', 2018, 's6');

-- --------------------------------------------------------

--
-- Table structure for table project
--

-- DROP TABLE IF EXISTS project;
CREATE TABLE IF NOT EXISTS project (
  leadSsn varchar(20) NOT NULL,
  pName varchar(45) NOT NULL,
  subject varchar(450) DEFAULT NULL,
  budget double DEFAULT NULL,
  startDate date NOT NULL,
  enddate date NOT NULL,
  controllingDName varchar(50) NOT NULL,
  PRIMARY KEY (leadSsn,pName),
  KEY fk_Project_department1_idx (controllingDName)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table project
--

INSERT INTO project (leadSsn, pName, `subject`, budget, startDate, enddate, controllingDName) VALUES
('i1', 'job1', 'about computers', 50000, '2019-06-01', '2021-01-01', 'Computer Engineering'),
('i6', 'job2', 'about life', 1000, '2018-02-02', '2020-06-06', 'Computer Engineering'),
('i7', 'job3', 'money talks', 1000000, '2018-01-01', '2021-01-01', 'Economics');

-- --------------------------------------------------------

--
-- Table structure for table project_has_gradstudent
--

-- DROP TABLE IF EXISTS project_has_gradstudent;
CREATE TABLE IF NOT EXISTS project_has_gradstudent (
  leadSsn varchar(20) NOT NULL,
  pName varchar(45) NOT NULL,
  Gradssn varchar(20) NOT NULL,
  workingHour int(11) DEFAULT NULL,
  PRIMARY KEY (leadSsn,pName,Gradssn),
  KEY fk_Project_has_GradStudent_GradStudent1_idx (Gradssn),
  KEY fk_Project_has_GradStudent_Project1_idx (leadSsn,pName)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table project_has_gradstudent
--

INSERT INTO project_has_gradstudent (leadSsn, pName, Gradssn, workingHour) VALUES
('i1', 'job1', 's3', 40),
('i6', 'job2', 's3', 12),
('i6', 'job2', 's6', 120),
('i7', 'job3', 's6', 8);

-- --------------------------------------------------------

--
-- Table structure for table project_has_instructor
--

-- DROP TABLE IF EXISTS project_has_instructor;
CREATE TABLE IF NOT EXISTS project_has_instructor (
  leadSsn varchar(20) NOT NULL,
  pName varchar(45) NOT NULL,
  issn varchar(20) NOT NULL,
  workinghour int(11) DEFAULT NULL,
  PRIMARY KEY (leadSsn,pName,issn),
  KEY fk_Project_has_instructor_instructor1_idx (issn),
  KEY fk_Project_has_instructor_Project1_idx (leadSsn,pName)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table project_has_instructor
--

INSERT INTO project_has_instructor (leadSsn, pName, issn, workinghour) VALUES
('i1', 'job1', 'i3', 12),
('i1', 'job1', 'i6', 15),
('i6', 'job2', 'i4', 12),
('i7', 'job3', 'i5', 8),
('i7', 'job3', 'i6', 4);

-- --------------------------------------------------------

--
-- Table structure for table sectionn
--

-- DROP TABLE IF EXISTS sectionn;
CREATE TABLE IF NOT EXISTS sectionn (
  issn varchar(20) NOT NULL,
  courseCode varchar(10) NOT NULL,
  yearr int(11) NOT NULL,
  semester int(11) NOT NULL,
  sectionId int(11) NOT NULL,
  quota int(11) NOT NULL,
  PRIMARY KEY (issn,courseCode,yearr,semester,sectionId),
  KEY fk_instructor_has_course_course1_idx (courseCode),
  KEY fk_instructor_has_course_instructor1_idx (issn)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table sectionn
--

INSERT INTO sectionn (issn, courseCode, yearr, semester, sectionId, quota) VALUES
('i1', 'comp2222', 2020, 2, 1, 40),
('i1', 'comp2222', 2020, 2, 2, 40),
('i6', 'comp1111', 2020, 2, 1, 40),
('i6', 'comp1111', 2020, 2, 2, 45),
('i8', 'INDE2156', 2020, 2, 1, 20),
('i9', 'INDE2156', 2020, 2, 2, 25),
('i9', 'math2103', 2020, 2, 1, 100);

-- --------------------------------------------------------

--
-- Table structure for table student
--

-- DROP TABLE IF EXISTS student;
CREATE TABLE IF NOT EXISTS student (
  ssn varchar(20) NOT NULL,
  gradorUgrad tinyint(4) NOT NULL,
  advisorSsn varchar(20) NOT NULL,
  currCode varchar(10) NOT NULL,
  dName varchar(50) NOT NULL,
  studentid varchar(10) NOT NULL,
  studentname varchar(45) NOT NULL,
  PRIMARY KEY (ssn),
  UNIQUE KEY studentid_UNIQUE (studentid),
  KEY fk_student_instructor1_idx (advisorSsn),
  KEY fk_student_curricula1_idx (currCode,dName)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table student
--

INSERT INTO student (ssn, gradorUgrad, advisorSsn, currCode, dName, studentid, studentname) VALUES
('s1', 0, 'i1', 'comp', 'Computer Engineering', 'st1', 'student1'),
('s2', 0, 'i6', 'comp', 'Computer Engineering', 'st2', 'student2'),
('s3', 1, 'i1', 'phd0813', 'Computer Engineering', 'mscomp1', 'gradstcse1'),
('s4', 0, 'i4', 'arch0813', 'Architecture', 'st3', 'student3'),
('s5', 0, 'i2', 'ie0813', 'Industrial Engineering', 'st4', 'student4'),
('s6', 1, 'i6', 'phd0813', 'Computer Engineering', 'mscomp2', 'gradstcse2');

-- --------------------------------------------------------

--
-- Table structure for table timeslot
--

-- DROP TABLE IF EXISTS timeslot;
CREATE TABLE IF NOT EXISTS timeslot (
  dayy varchar(12) NOT NULL,
  hourr int(11) NOT NULL,
  PRIMARY KEY (dayy,hourr)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table timeslot
--

INSERT INTO timeslot (dayy, hourr) VALUES
('Friday', 1),
('Friday', 2),
('Friday', 3),
('Friday', 4),
('Friday', 5),
('Friday', 6),
('Friday', 7),
('Friday', 8),
('Friday', 9),
('Friday', 10),
('Friday', 11),
('Friday', 12),
('Friday', 13),
('Monday', 1),
('Monday', 2),
('Monday', 3),
('Monday', 4),
('Monday', 5),
('Monday', 6),
('Monday', 7),
('Monday', 8),
('Monday', 9),
('Monday', 10),
('Monday', 11),
('Monday', 12),
('Monday', 13),
('Saturday', 1),
('Saturday', 2),
('Saturday', 3),
('Saturday', 4),
('Saturday', 5),
('Saturday', 6),
('Thursday', 1),
('Thursday', 2),
('Thursday', 3),
('Thursday', 4),
('Thursday', 5),
('Thursday', 6),
('Thursday', 7),
('Thursday', 8),
('Thursday', 9),
('Thursday', 10),
('Thursday', 11),
('Thursday', 12),
('Thursday', 13),
('Tuesday', 1),
('Tuesday', 2),
('Tuesday', 3),
('Tuesday', 4),
('Tuesday', 5),
('Tuesday', 6),
('Tuesday', 7),
('Tuesday', 8),
('Tuesday', 9),
('Tuesday', 10),
('Tuesday', 11),
('Tuesday', 12),
('Tuesday', 13),
('Wednesday', 1),
('Wednesday', 2),
('Wednesday', 3),
('Wednesday', 4),
('Wednesday', 5),
('Wednesday', 6),
('Wednesday', 7),
('Wednesday', 8),
('Wednesday', 9),
('Wednesday', 10),
('Wednesday', 11),
('Wednesday', 12),
('Wednesday', 13);

-- --------------------------------------------------------

--
-- Table structure for table weeklyschedule
--

-- DROP TABLE IF EXISTS weeklyschedule;
CREATE TABLE IF NOT EXISTS weeklyschedule (
  issn varchar(20) NOT NULL,
  courseCode varchar(10) NOT NULL,
  sectionId int(11) NOT NULL,
  yearr int(11) NOT NULL,
  semester int(11) NOT NULL,
  dayy varchar(12) NOT NULL,
  hourr int(11) NOT NULL,
  buildingName varchar(50) NOT NULL,
  roomNumber int(11) NOT NULL,
  PRIMARY KEY (issn,courseCode,sectionId,yearr,semester,dayy,hourr),
  KEY fk_section_has_TimeSlot_TimeSlot1_idx (dayy,hourr),
  KEY fk_section_has_TimeSlot_section1_idx (issn,courseCode,yearr,semester,sectionId),
  KEY fk_section_has_TimeSlot_buildingAndroom1_idx (buildingName,roomNumber)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table weeklyschedule
--

INSERT INTO weeklyschedule (issn, courseCode, sectionId, yearr, semester, dayy, hourr, buildingName, roomNumber) VALUES
('i1', 'comp2222', 1, 2020, 2, 'Monday', 5, 'dk', 101),
('i1', 'comp2222', 1, 2020, 2, 'Monday', 6, 'dk', 101),
('i1', 'comp2222', 1, 2020, 2, 'Monday', 7, 'dk', 101),
('i1', 'comp2222', 2, 2020, 2, 'Tuesday', 1, 'dk', 501),
('i1', 'comp2222', 2, 2020, 2, 'Tuesday', 2, 'dk', 501),
('i1', 'comp2222', 2, 2020, 2, 'Tuesday', 3, 'dk', 501),
('i8', 'INDE2156', 1, 2020, 2, 'Thursday', 1, 'dmf', 213),
('i8', 'INDE2156', 1, 2020, 2, 'Thursday', 2, 'dmf', 213),
('i8', 'INDE2156', 1, 2020, 2, 'Thursday', 3, 'dmf', 213),
('i9', 'INDE2156', 2, 2020, 2, 'Thursday', 1, 'dmf', 311),
('i9', 'INDE2156', 2, 2020, 2, 'Thursday', 2, 'dmf', 311),
('i9', 'INDE2156', 2, 2020, 2, 'Thursday', 3, 'dmf', 311),
('i9', 'math2103', 1, 2020, 2, 'Friday', 1, 'dmf', 411),
('i9', 'math2103', 1, 2020, 2, 'Friday', 2, 'dmf', 411),
('i9', 'math2103', 1, 2020, 2, 'Friday', 3, 'dmf', 411),
('i9', 'math2103', 1, 2020, 2, 'Friday', 4, 'dmf', 411),
('i6', 'comp1111', 1, 2020, 2, 'Wednesday', 1, 'lmf', 321),
('i6', 'comp1111', 1, 2020, 2, 'Wednesday', 2, 'lmf', 321),
('i6', 'comp1111', 1, 2020, 2, 'Wednesday', 3, 'lmf', 321),
('i6', 'comp1111', 2, 2020, 2, 'Wednesday', 5, 'lmf', 321),
('i6', 'comp1111', 2, 2020, 2, 'Wednesday', 6, 'lmf', 321),
('i6', 'comp1111', 2, 2020, 2, 'Wednesday', 7, 'lmf', 321);

--
-- Constraints for dumped tables
--

--
-- Constraints for table course
--
ALTER TABLE course
  ADD CONSTRAINT fk_course_course1 FOREIGN KEY (prereq_courseCode) REFERENCES course (courseCode) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table curricula
--
ALTER TABLE curricula
  ADD CONSTRAINT fk_curricula_department1 FOREIGN KEY (dName) REFERENCES department (dName) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table curriculacourses
--
ALTER TABLE curriculacourses
  ADD CONSTRAINT fk_curricula_has_course_course1 FOREIGN KEY (courseCode) REFERENCES course (courseCode) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fk_curricula_has_course_curricula1 FOREIGN KEY (currCode,dName) REFERENCES curricula (currCode, dName) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table department
--
ALTER TABLE department
  ADD CONSTRAINT fk_department_building1 FOREIGN KEY (buildingName) REFERENCES buildingandroom (buildingName) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fk_department_instructor1 FOREIGN KEY (headSsn) REFERENCES instructor (ssn) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table emails
--
ALTER TABLE emails
  ADD CONSTRAINT fk_emails_student1 FOREIGN KEY (sssn) REFERENCES student (ssn) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table enrollment
--
ALTER TABLE enrollment
  ADD CONSTRAINT fk_student_has_section_section1 FOREIGN KEY (issn,courseCode,yearr,semester,sectionId) REFERENCES sectionn (issn, courseCode, yearr, semester, sectionId) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT fk_student_has_section_student1 FOREIGN KEY (sssn) REFERENCES student (ssn) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table gradstudent
--
ALTER TABLE gradstudent
  ADD CONSTRAINT fk_GradStudent_instructor1 FOREIGN KEY (supervisorSsn) REFERENCES instructor (ssn) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fk_GradStudent_student1 FOREIGN KEY (ssn) REFERENCES student (ssn) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table instructor
--
ALTER TABLE instructor
  ADD CONSTRAINT fk_instructor_department FOREIGN KEY (dName) REFERENCES department (dName) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table prevdegree
--
ALTER TABLE prevdegree
  ADD CONSTRAINT fk_PrevDegree_GradStudent1 FOREIGN KEY (Gradssn) REFERENCES gradstudent (ssn) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table project
--
ALTER TABLE project
  ADD CONSTRAINT fk_Project_department1 FOREIGN KEY (controllingDName) REFERENCES department (dName) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fk_Project_instructor1 FOREIGN KEY (leadSsn) REFERENCES instructor (ssn) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table project_has_gradstudent
--
ALTER TABLE project_has_gradstudent
  ADD CONSTRAINT fk_Project_has_GradStudent_GradStudent1 FOREIGN KEY (Gradssn) REFERENCES gradstudent (ssn) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fk_Project_has_GradStudent_Project1 FOREIGN KEY (leadSsn,pName) REFERENCES project (leadSsn, pName) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table project_has_instructor
--
ALTER TABLE project_has_instructor
  ADD CONSTRAINT fk_Project_has_instructor_Project1 FOREIGN KEY (leadSsn,pName) REFERENCES project (leadSsn, pName) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT fk_Project_has_instructor_instructor1 FOREIGN KEY (issn) REFERENCES instructor (ssn) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table sectionn
--
ALTER TABLE sectionn
  ADD CONSTRAINT fk_instructor_has_course_course1 FOREIGN KEY (courseCode) REFERENCES course (courseCode) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT fk_instructor_has_course_instructor1 FOREIGN KEY (issn) REFERENCES instructor (ssn) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table student
--
ALTER TABLE student
  ADD CONSTRAINT fk_student_curricula1 FOREIGN KEY (currCode,dName) REFERENCES curricula (currCode, dName) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fk_student_instructor1 FOREIGN KEY (advisorSsn) REFERENCES instructor (ssn) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table weeklyschedule
--
ALTER TABLE weeklyschedule
  ADD CONSTRAINT fk_section_has_TimeSlot_TimeSlot1 FOREIGN KEY (dayy,hourr) REFERENCES timeslot (dayy, hourr) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fk_section_has_TimeSlot_buildingAndroom1 FOREIGN KEY (buildingName,roomNumber) REFERENCES buildingandroom (buildingName, roomNumber) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fk_section_has_TimeSlot_section1 FOREIGN KEY (issn,courseCode,yearr,semester,sectionId) REFERENCES sectionn (issn, courseCode, yearr, semester, sectionId) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
