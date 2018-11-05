Dear {{$student->user->name}},

Here is the result of the assessments of the course - {{$course->code}} {{$course->name}}

@if($intraGroupAssessments->count() > 0)
Intra-group peer assessments and self-assessments on individual’s contribution
@foreach($intraGroupAssessments as $assessment)
{{$assessment->name}}: {{number_format($intraGroupAssessmentMarks->where('assessmentId', $assessment->id)->first()->marks, 2, '.', '')}} marks
@endforeach
@endif

@if($groupAssessments->count() > 0)
Inter-group peer assessments on presentation/report
@foreach($groupAssessments as $assessment)
{{$assessment->name}}: {{number_format($groupAssessmentMarks->where('assessmentId', $assessment->id)->first()->marks, 2, '.', '')}} marks
@if($assessment->responses->where('groupId', $student->group_id)->count() > 0)
Responses raised for this assessment:
@foreach($assessment->responses->where('groupId', $student->group_id) as $response)
{{$response->content}}
@endforeach
@endif

@endforeach
@endif

Regards,
Lecturer of {{$course->code}} {{$course->name}}
