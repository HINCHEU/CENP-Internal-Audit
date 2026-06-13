<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Role</th>
            <th>Department</th>
            @foreach($evaluations as $evaluation)
                <th colspan="2">{{ $evaluation->title }}</th>
            @endforeach
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            @foreach($evaluations as $evaluation)
                <th>Score</th>
                <th>Comment</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->gender ?? '-' }}</td>
                <td>{{ $user->role === 'admin' ? 'Administrator' : 'User' }}</td>
                <td>{{ $user->department->name ?? '-' }}</td>
                
                @foreach($evaluations as $evaluation)
                    @php
                        $score = $user->evaluationScores->where('evaluation_id', $evaluation->id)->first();
                    @endphp
                    <td>{{ $score ? $score->score : '-' }}</td>
                    <td>{{ $score && $score->comment ? $score->comment : '-' }}</td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ 5 + ($evaluations->count() * 2) }}">No evaluators found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
