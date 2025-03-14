import React from "react";

const CourseCard = ({ course }) => {
    return (
        <div className="course-card">
            <h3>{course.course_name}</h3>
            <p><strong>Available Quantity:</strong> {course.available_quantity}</p>
            <p><strong>Enrolled Students:</strong> {course.enrolled_students}</p>
        </div>
    );
};

export default CourseCard;
