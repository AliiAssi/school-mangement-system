
---

## Database Schema Design

The database schema is meticulously crafted to handle educational data efficiently. It consists of the following primary tables:

- **`classes`**: Contains information about each class. Essential columns include:
  - `id`: Unique identifier for the class.
  - `name`: Name of the class.
  - `grade`: Associated grade level.

- **`timetable`**: Manages class scheduling with the following key columns:
  - `class_id`: Foreign key referencing the `classes` table.
  - `teacher_id`: Foreign key referencing the `users` table (teachers).
  - `subject_id`: Foreign key referencing the `subjects` table.
  - `start_time`: Class start time.
  - `end_time`: Class end time.
  - `day_of_week`: Day on which the class takes place.

- **`teacher_subject_class`**: A pivot table connecting teachers, subjects, and classes. Key columns are:
  - `class_id`: Foreign key referencing the `classes` table.
  - `user_id`: Foreign key referencing the `users` table.
  - `subject_id`: Foreign key referencing the `subjects` table.
  - `weekly_sessions`: Number of weekly sessions for the subject in the class.

- **`class_subject`**: A pivot table handling the many-to-many relationship between classes and subjects. Key columns are:
  - `class_id`: Foreign key referencing the `classes` table.
  - `subject_id`: Foreign key referencing the `subjects` table.
  - `required_sessions`: Number of sessions required for the subject in the class.

The `users` table includes an `is_admin` flag, a boolean field that determines administrative privileges. By default, this flag is set to `false` for regular users.

Foreign key constraints ensure data integrity and proper relationships between tables.

![Database Schema](./demo/db/schema.png)

## Authentication

Authentication is managed using Laravel Breeze. New users are initialized as teachers with standard privileges by setting the `is_admin` flag to `0` by default. Administrative privileges can be assigned as needed.

## User Interface Design

The application features a modern and responsive design powered by Tailwind CSS. Key components include:

### Dashboard

![Dashboard](./demo/dashboard.png)

### Classes CRUD Interfaces

- **View Page**:  
  ![Classes View Page](./demo/classes/index.png)

- **Create Page**:  
  ![Classes Create Page](./demo/classes/create.png)

- **Update Page**:  
  ![Classes Update Page](./demo/classes/edit.png)

### Subjects CRUD Interfaces

- **View Page**:  
  ![Subjects View Page](./demo/subjects/index.png)

- **Show Page**:  
  ![Subjects Show Page](./demo/subjects/show.png)

- **Create Page**:  
  ![Subjects Create Page](./demo/subjects/create.png)

- **Update Page**:  
  ![Subjects Update Page](./demo/subjects/edit.png)

### Teachers CRUD Interfaces

- **View Page**:  
  ![Teachers View Page](./demo/teachers/index.png)

- **Show Page**:  
  ![Teachers Show Page](./demo/teachers/show.png)

- **Create Page**:  
  ![Teachers Create Page](./demo/teachers/create.png)

- **Update Page**:  
  ![Teachers Update Page](./demo/teachers/edit.png)

**Note**: Certain features, such as timetable management, are still under development.

---