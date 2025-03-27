<?php

require "family.php";  // Ensure this file contains necessary data and database connection
$grupoCom = [];

foreach ($grupos as $grup) {
    foreach ($grup as $subA) {
        $i = 0;
        $items = [];
        $compatativo = [];

        // Query to get items related to the current part number ($subA)
        $buscar = mysqli_query($con, "SELECT * FROM datos 
                                      WHERE part_num = '$subA' 
                                      AND item NOT LIKE 'WTXL-%' 
                                      AND item NOT LIKE 'WGXL-%' 
                                      AND item NOT LIKE 'WSGX-%' 
                                      AND item NOT LIKE 'LTP%' 
                                      AND item NOT LIKE 'LW-%' 
                                      AND item NOT LIKE 'TAPE-25'");

        if (!$buscar) {
            echo "Error in query: " . mysqli_error($con);
            continue;
        }

        // Process the first query results
        while ($row = mysqli_fetch_array($buscar)) {
            $item = $row['item'];
            $items[$subA][$i] = $item;
            $i++;
        }

        $totalItems = mysqli_num_rows($buscar); // Total items for compatibility percentage calculation

        // Process the items to calculate compatibility with other part numbers
        foreach ($items as $key => $valueArray) {
            foreach ($valueArray as $value) {
                // Query for comparing items with other part numbers
                $buscarcomp1 = mysqli_query($con, "SELECT * FROM datos WHERE item = '$value' AND part_num != '$subA'");

                if (!$buscarcomp1) {
                    echo "Error in comparison query: " . mysqli_error($con);
                    continue;
                }

                while ($row1 = mysqli_fetch_array($buscarcomp1)) {
                    $part_num = $row1['part_num'];

                    // Add to the comparison array
                    if (isset($compatativo[$key][$part_num])) {
                        $compatativo[$key][$part_num] += 1;
                    } else {
                        $compatativo[$key][$part_num] = 1;
                    }
                }
            }
        }

        // Calculate compatibility and store results based on thresholds (75% or 60%-74%)
        foreach ($compatativo as $key => $comparisons) {
            foreach ($comparisons as $part_num => $count) {
                $compatibility = ($count / $totalItems) * 100;

                // If compatibility >= 75%, or 60% <= compatibility < 75%
                if ($compatibility >= 75) {
                    $grupoCom[$subA][$key][$part_num] = round($compatibility, 2);
                } elseif ($compatibility >= 60 && $compatibility < 75) {
                    $grupoCom[$subA][$key][$part_num] = round($compatibility, 2);
                }
            }
        }
    }
}

// Return the results
return $grupoCom;
?>
