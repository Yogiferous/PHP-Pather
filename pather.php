<?php
/**
 * User: Chris Hyde
 * Date: 9/23/2015
 * Time: 8:36 PM
 */

function main() {
    # Get arguments
    $paths = get_arguments();

    # Get input data
    $pather_matrix = get_input_data($paths[0]);

    # Initialize list to hold hashes found
    $hashes_list = find_those_hashes( $pather_matrix );

    # Connect those hashes!!!
    $pather_matrix = connect_the_hashes( $pather_matrix, $hashes_list );

    # Write updated pather_matrix to file
    write_to_output_path($pather_matrix, $paths[1]);
}

function get_arguments(){
    # Define global arguments variable
    global $argv;

    # Validate the correct number of arguments were provided
    if( sizeof($argv) == 3 or die("Invalid Arguments") ){
        # Set input and output paths
        $paths = array($argv[1],$argv[2]);

        # Return Paths back to main
        return $paths;
    }
}

function get_input_data($input_path) {
    # Initialize matrix to hold input data
    $pather_matrix = array();

    # Open input file
    $input_file = fopen($input_path, "r") or die("Unable to open file!");

    # Read data from input file and store into matrix
    while(! feof($input_file))
    {
        $line = fgets($input_file);
        $temp_array = str_split($line);
        array_push($pather_matrix, $temp_array);
    }

    # Close input file
    fclose($input_file);

    # Return data matrix
    return $pather_matrix;
}

function find_those_hashes( $pather_matrix ) {
    # Initialize list to hold hashes found
    $hashes = array();

    # Find hashes in matrix
    for ($x = 0; $x < sizeof($pather_matrix); $x++) {
        for ($y = 0; $y < sizeof($pather_matrix[$x]); $y++) {
            if ($pather_matrix[$x][$y] == '#') {
                $hash = array($x, $y);
                array_push($hashes, $hash);
            }
        }
    }

    # Return hash coordinates
    return $hashes;
}

function connect_the_hashes( $pather_matrix, $hashes_list ) {
    $count = 0;
    # While hashes are not connected
    while( count( $hashes_list ) > 1) {
        # Determine starting x position
        $starting_x = $hashes_list[0][0];

        # Determine starting y position
        $starting_y = $hashes_list[0][1];

        # Determine differences between vertical coordinates
        $vertical_difference = $hashes_list[1][0] - $hashes_list[0][0];

        # Determine differences between horizontal coordinates
        $horizontal_difference = $hashes_list[1][1] - $hashes_list[0][1];

        # Draw vertical *
        if ($vertical_difference > 0) {
            for ($x = 1; $x < $vertical_difference + 1; $x++) {
                $pather_matrix[$starting_x + $x][$starting_y] = '*';
            }
        }

        # Draw horizontal *
        if ($horizontal_difference != 0) {
            for ($x = min($horizontal_difference + 1, 1); $x < max($horizontal_difference, 1); $x++) {
                $pather_matrix[$starting_x + $vertical_difference][$starting_y + $x] = '*';
            }
        }

        # Remove first hash coordinates from hash list
        $hashes_list = array_slice($hashes_list,1);
    }
    # Return updated matrix
    return $pather_matrix;
}

function write_to_output_path($pather_matrix, $output_path) {
    # Open Output File
    $output_file = fopen($output_path, "w") or die("Unable to open file!");

    # Write pather_matrix to file
    for ($x = 0; $x < sizeof($pather_matrix); $x++) {
        for ($y = 0; $y < sizeof($pather_matrix[$x]); $y++) {
            fwrite($output_file, $pather_matrix[$x][$y]);
        }
    }

    # Close Output file
    fclose($output_file);
}

# Run Main
main();
?>