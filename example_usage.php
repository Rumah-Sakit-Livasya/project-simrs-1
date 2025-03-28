<?php

require 'app/AgeComparison.php';

$minAge = new Age(12, 8, 20);
$maxAge = new Age(16, 8, 1);
$ageComparison = new AgeComparison($minAge, $maxAge);

$dob = '2008-01-01';
if ($ageComparison->isAgeWithinRange($dob)) {
    echo "The person's age is within the range.";
} else {
    echo "The person's age is not within the range.";
}
?>
