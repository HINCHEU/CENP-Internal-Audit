<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Project</th>
            @foreach($dates as $date)
                <th>{{ $date->format('d/m/Y') }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($projects as $index => $project)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $project->name }} {{ $project->project_code ? '(' . $project->project_code . ')' : '' }}</td>
                
                @foreach($dates as $date)
                    @php
                        $dateKey = $date->format('Y-m-d');
                        $score = $projectScores[$project->id][$dateKey] ?? null;
                    @endphp
                    <td>
                        @if($score !== null)
                            {{ number_format($score, 2) }}%
                        @else
                            -
                        @endif
                    </td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ 2 + $dates->count() }}">No projects found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
