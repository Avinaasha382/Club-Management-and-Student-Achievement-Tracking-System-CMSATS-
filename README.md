# Club Management and Student Achievement Tracking System (CMSATS)

## Overview
The CMSATS project aims to develop a comprehensive software solution for managing student clubs and tracking extracurricular achievements at IIITDM. It includes features for organizing club activities, tracking student participation, generating reports, and automating various club management tasks.

## Contents
- Introduction
- Software Requirement Specifications (SRS)
- Entity Relationship Model (ER)
- Schema Mapping
- Technology Stack
- Implementation and Methodology
- Data Population in SQL
- Front-end
- SQL Queries used in the Backend
- Workflow Diagram

## Introduction
Student clubs play a vital role in fostering personal growth, academic achievement, and social interaction among students. IIITDM's clubs cater to various interests, including technical, cultural, and competitive domains. CMSATS is designed to streamline the management of these clubs by automating tasks, providing secure access, and enhancing transparency.

### Purpose
The primary goal of CMSATS is to automate the manual tracking of student involvement in diverse club activities, enabling efficient management and comprehensive performance assessment.

### Project Scope
The project encompasses developing a centralized system for:
- Managing club activities and events.
- Tracking student participation.
- Generating detailed reports on student achievements.
- Providing a user-friendly interface for students, professors, and administrators.

## Software Requirement Specifications (SRS)

### Functional Requirements
1. **User Management**
   - Authentication using unique IDs and passwords.
   - Role-based access control for different user roles.

2. **Club Management**
   - Create, update, and delete clubs with unique IDs.
   - Manage roles within clubs (e.g., cores, coordinators).

3. **Event Management**
   - Schedule events with conflict detection.
   - Classify events as certifiable or non-certifiable.
   - Automated notifications for event updates.

4. **Project Management**
   - Create and manage projects for competitive clubs.
   - Associate projects with specific clubs.

5. **Participation Tracking**
   - Record student participation in events and projects.
   - Generate performance reports based on participation.

6. **Feedback Management**
   - Collect and display feedback for events.

7. **Reporting**
   - Generate customizable reports on club activities and student achievements.
   - Export reports in multiple formats (PDF, Excel).

### Non-Functional Requirements
1. **Performance**: Fast response times and efficient data handling.
2. **Scalability**: Support for increasing numbers of users and data.
3. **Security**: Role-based access control and secure authentication.
4. **Usability**: Intuitive user interface with accessibility support.
5. **Maintainability**: Modular design and proper documentation.
6. **Compatibility**: Cross-platform compatibility for web browsers and devices.

## Entity Relationship Model (ER)
The ER model defining the relationships between entities such as clubs, members, events, projects, and feedback is included.

## Schema Mapping
The schema mapping outlining the database structure, including tables for users, clubs, events, projects, and participation records is included.

## Technology Stack
1. **Operating System**: Compatible with all major OS.
2. **Database**: MySQL.
3. **Frontend**: HTML, CSS, JavaScript.
4. **Backend**: PHP, Node.js, Express.js.
5. **Web Server**: Apache HTTP.
6. **Programming Languages**: Python, PHP.

## Implementation and Methodology
1. **User Authentication**: Role-based login system.
2. **Centralized Club Website**: Provides access to individual club pages.
3. **Event Scheduling**: Dynamic scheduler with conflict detection.
4. **Notification System**: Automated email notifications.
5. **Achievement Tracking**: Personalized dashboards for students with automated certificate generation.

## Data Population in SQL
Details on how the database was populated with sample data for testing and demonstration purposes is included.

## Front-end
The frontend was developed using HTML, CSS, and JavaScript, ensuring a responsive and user-friendly design.

## SQL Queries Used in the Backend
Comprehensive list of SQL queries for CRUD operations, user authentication, event scheduling, and reporting is included.

## Workflow Diagram
A detailed workflow diagram illustrating the system’s operation, including user interactions, data flow, and process automation is included.

## Conclusion
CMSATS provides a robust solution for managing and tracking student club activities at IIITDM. With its comprehensive feature set, it enhances the efficiency of club management and promotes student engagement through streamlined processes and transparent reporting.
