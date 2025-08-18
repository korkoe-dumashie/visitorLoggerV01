<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\VisitorAccessCard;
use Illuminate\Http\Request;

class VisitorAccessCardController extends Controller
{
    //show all visitor access cards
    public function create()
    {
        return view('visitor_access_card.create');
    }

    public function index(){
        $visitorAccessCards = VisitorAccessCard::simplePaginate(15);
        return view('visitor_access_card.index',compact('visitorAccessCards'));
    }

    public function store(){
        request()->validate([
            'card_number' => 'required',
        ]);

        VisitorAccessCard::create([
            'card_number' => request('card_number'),
            'status'=> 'available',
            'active' => 'enabled', // Added default active status
        ]);

        Activities::log(
            action: 'Created New Visitor Access Card.',
            description: 'New Card with ID: ' . request('card_number')
        );

        // Add success flash message
        session()->flash('success', 'Card ' . request('card_number') . ' has been created successfully.');

        return redirect('access-cards');
    }

    public function disable(VisitorAccessCard $visitorAccessCard){
        $visitorAccessCard->update([
            'active' => 'disabled',
            'status'=> 'unavailable',
        ]);

        Activities::log(
            action: 'Disabled Visitor Access Card.',
            description: 'Card with ID: ' . $visitorAccessCard->card_number
        );

        // Add success flash message
        session()->flash('success', 'Card ' . $visitorAccessCard->card_number . ' has been disabled.');

        return redirect('access-cards');
    }
    
    public function enable(VisitorAccessCard $visitorAccessCard){
        $visitorAccessCard->update([
            'active' => 'enabled', // This value is now correct
            'status'=> 'available',
        ]);

        Activities::log(
            action: 'Enabled Visitor Access Card.',
            description: 'Card with ID: ' . $visitorAccessCard->card_number
        );

        // Add success flash message
        session()->flash('success', 'Card ' . $visitorAccessCard->card_number . ' has been enabled.');

        return redirect('access-cards');
    }
}