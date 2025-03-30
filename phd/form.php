<?php

$jsonString = '{
    "indian": [
        {
            "name": "Dr. Rajesh Sharma",
            "email": "rajesh.sharma@iitb.ac.in",
            "home_page": "https://iitb.ac.in/rajesh",
            "telephone": "+91-9876543210",
            "designation": "Professor",
            "office_address": "IIT Bombay, India",
            "specialization": "Machine Learning"
        },
        {
            "name": "Dr. Anjali Verma",
            "email": "anjali.verma@iitm.ac.in",
            "home_page": "https://iitm.ac.in/anjali",
            "telephone": "+91-9765432109",
            "designation": "Associate Professor",
            "office_address": "IIT Madras, India",
            "specialization": "Computer Vision"
        }
    ]
}';

$panelOfExaminers = json_decode($jsonString, true);

if ($panelOfExaminers === null) {
    echo "JSON Error: " . json_last_error_msg();
} else {
    echo "<pre>";
    print_r($panelOfExaminers);
    echo "</pre>";
}

// if (is_array($jsonString)) {
//     $panelOfExaminers = $jsonString; // It's already an array!
// } else {
//     $panelOfExaminers = json_decode($jsonString, true);
// }

foreach($panelOfExaminers['indian'] as $examiner) {
    echo "<h3>{$examiner['name']}</h3>";
    echo "<p>Email: {$examiner['email']}</p>";
    echo "<p>Home Page: <a href='{$examiner['home_page']}'>{$examiner['home_page']}</a></p>";
    echo "<p>Telephone: {$examiner['telephone']}</p>";
    echo "<p>Designation: {$examiner['designation']}</p>";
    echo "<p>Office Address: {$examiner['office_address']}</p>";
    echo "<p>Specialization: {$examiner['specialization']}</p>";
}