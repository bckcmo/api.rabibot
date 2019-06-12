@component('mail::message')

Hi There. It's me, Rabi. You requested an EJ Screen, so here are the results.

The area surrounding {{$screen->address}} {{$screen->city}}, {{$screen->state}} {{$screen->zip}} {{$resultText}} an EJ area.

<a href="{{$screen->one_mile_report}}" title="One Mile Report">Click to view the one mile report</a>.
<br>
<a href="{{$screen->blockgroup_report}}" title="Blockgroup Report">Click to view the blockgroup report</a>.

Thank you!<br>
Rabibot
@endcomponent
