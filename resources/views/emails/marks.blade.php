@component('mail::layout')
Dear {{$student->user->name}},

It is to inform you that the following assessment(s) of {{$course->code}} has/have been graded.

@if($intraGroupAssessments->count() + $groupAssessments->count() > 0)
@component('mail::table')
|               | Marks         |
| ------------- | ------------- |
@foreach($intraGroupAssessments as $assessment)
|{{$assessment->name}}|{{number_format($intraGroupAssessmentMarks->where('assessmentId', $assessment->id)->first()->marks, 2, '.', '')}} / 100|
@endforeach
@foreach($groupAssessments as $assessment)
|{{$assessment->name}}|{{number_format($groupAssessmentMarks->where('assessmentId', $assessment->id)->first()->marks, 2, '.', '')}} / 100|
@endforeach
@endcomponent
@endif
<br/>
@foreach($groupAssessmentResponses as $assessmentResponses)
Responses raised for {{$groupAssessments->where('id', $assessmentResponses->assessmentId)->first()->name}}
@foreach($assessmentResponses->responses as $groupId => $responses)
@component('mail::table')

|               | Group {{$course->groups->where('id', $groupId)->first()->name}}|
| ------------- | ------------- |
@foreach($responses as $response)
|{{$response->fromUser->user->name}}|{{$response->content}}|
@endforeach
@endcomponent
<br/>
@endforeach
@endforeach

Regards,<br>
Lecturer of {{$course->code}}
@endcomponent
