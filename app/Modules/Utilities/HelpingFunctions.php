<?php
namespace App\Modules\Utilities;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class HelpingFunctions
{
    public static function paginate(Collection $collection)
    {
        $itemsPerPage = 10; // Number of items to display per page
        $pageNumber = 1; // The current page number
        
        // Assuming you have a collection called $collection containing your data
        
        $totalItems = $collection->count(); // Total number of items in the collection
        
        $offset = ($pageNumber - 1) * $itemsPerPage; // Calculate the offset for the slice
        
        $slicedItems = $collection->slice($offset, $itemsPerPage); // Get a slice of items for the current page
        
        $paginator = new LengthAwarePaginator($slicedItems, $totalItems, $itemsPerPage, $pageNumber);
        return $paginator;
        
    }

  
}

