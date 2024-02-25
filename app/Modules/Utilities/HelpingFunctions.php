<?php
namespace App\Modules\Utilities;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class HelpingFunctions
{
    public static function paginate(Collection $collection,int $page)
    {
        $itemsPerPage = 10; // Number of items to display per page
        $pageNumber = $page; // The current page number
        
        // Assuming you have a collection called $collection containing your data
        
        $totalItems = $collection->count(); // Total number of items in the collection
        
        $offset = ($pageNumber - 1) * $itemsPerPage; // Calculate the offset for the slice
        
        $slicedItems = $collection->slice($offset, $itemsPerPage); // Get a slice of items for the current page
        
        $paginator = new LengthAwarePaginator($slicedItems, $totalItems, $itemsPerPage, $pageNumber);
        return $paginator;
        
    }

    function mergeArraysReplaceOverlapping($array1, $array2) {
        // Iterate over the first array
        foreach ($array1 as $key => $value) {
            // Check if the key exists in the second array
            if (array_key_exists($key, $array2)) {
                // Replace the value in the first array with the value from the second array
                $array1[$key] = $array2[$key];
            } else {
                // If the key doesn't exist in the second array, add it to the merged array
                $array1[$key] = $value;
            }
        }
        
        // Merge any remaining elements from the second array into the merged array
        foreach ($array2 as $key => $value) {
            if (!array_key_exists($key, $array1)) {
                $array1[$key] = $value;
            }
        }
        
        return $array1;
    }
}

