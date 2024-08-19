
---

### Problem Description:

1. **Class Subject Requirements:**
   - Each subject within a class has a `required_sessions` value, which represents the total number of sessions needed in the timetable.
   - Each subject in a class may be taught by one or more teachers.

2. **Data Tables:**
   - **`class_subject`**: Indicates the number of required sessions for each subject within a class.
   - **`teacher_subject_class`**: Shows the maximum number of weekly hours a teacher can teach a particular subject to a class.
   - **`timetables`**: Contains information about the start and end times of sessions, along with `user_id`, `subject_id`, and `class_id`.

3. **Time Constraints:**
   - Start hour: 8 AM
   - End hour: 5 PM

4. **Task Constraints:**
   - No session conflicts are allowed.
   - A teacher cannot exceed their weekly teaching limit for a subject in a class.
   - No teacher can have overlapping sessions within the same class or across different classes at the same time.
   - A teacher cannot be assigned sessions if the total number of sessions for the subject in the class exceeds the `required_sessions`.

5. **Queries to Use:**
   - To check weekly teaching limits: 
     ```sql
     SELECT `class_id`, `user_id`, `subject_id`, `weekly_sessions` FROM `teacher_subject_class` WHERE ...
     ```
   - To verify current sessions for a class and subject:
     ```sql
     SELECT `class_id`, `subject_id`, `start_time`, `end_time` FROM `timetables` WHERE ...
     ```
     Compute the total duration of sessions by summing `(end_time - start_time)`.

6. **Task Objective:**
   - Generate automatic timetables for all classes. 
   - Setup in mentioned with many constraints as break and start time and end time and such that off days and sessions delay 
   - **Implementation Steps:**
     1. For each class:
        - Retrieve all related subjects.
        - For each day of the week (e.g., Monday through Sunday):
          - For each subject:
            - Identify available teachers for this subject in the class.
            - Ensure the teacher has not exceeded the weekly session limit.
            - Check that the teacher’s sessions do not overlap with existing sessions on the same day or in other classes.
            - If the teacher meets all conditions:
              - Create a timetable entry:
                ```sql
                INSERT INTO `timetables` (`class_id`, `user_id`, `subject_id`, `start_time`, `end_time`, `day_of_week`) VALUES (...)
                ```
