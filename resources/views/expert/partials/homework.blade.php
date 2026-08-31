@php($hw = $homework ?? [])
@if(!empty($hw['tasks']))
    <section class="bns-expert-mehul__homework wow fadeInUp" data-wow-duration="0.85s">
        <div class="bns-vision-header bns-expert-mehul__framework-head">
            <span class="bns-vision-header__label">{{ $hw['label'] ?? 'Home Work' }}</span>
            <h3>{!! bns_rich_text($hw['title'] ?? '') !!}</h3>
            @if(!empty($hw['subtitle']))
                <p class="bns-expert-mehul__framework-subtitle">{!! bns_rich_text($hw['subtitle']) !!}</p>
            @endif
        </div>

        @php($theme = $hw['theme'] ?? [])
        @if(!empty($theme['quote']) || !empty($theme['lines']))
            <div class="bns-expert-mehul__hw-theme">
                @if(!empty($theme['label']))
                    <span class="bns-expert-mehul__motto-label">{{ $theme['label'] }}</span>
                @endif
                @if(!empty($theme['quote']))
                    <p class="bns-expert-mehul__hw-theme-quote">{!! bns_rich_text($theme['quote']) !!}</p>
                @endif
                @if(!empty($theme['intro']))
                    <p class="bns-expert-mehul__hw-theme-intro">{!! bns_rich_text($theme['intro']) !!}</p>
                @endif
                @if(!empty($theme['lines']))
                    @foreach($theme['lines'] as $line)
                        <p class="bns-expert-mehul__hw-theme-line{{ $loop->last ? ' is-accent' : '' }}">
                            {!! bns_rich_text($line) !!}
                        </p>
                    @endforeach
                @endif
            </div>
        @endif

        @if(!empty($hw['tasks_title']))
            <p class="bns-expert-mehul__hw-tasks-title">{{ $hw['tasks_title'] }}</p>
        @endif

        <div class="bns-expert-mehul__hw-tasks">
            @foreach($hw['tasks'] as $task)
                <article class="bns-expert-mehul__hw-task">
                    <div class="bns-expert-mehul__hw-task-head">
                        <span class="bns-expert-mehul__letter">{{ $task['number'] ?? $loop->iteration }}</span>
                        <h4>{!! bns_rich_text($task['title'] ?? '') !!}</h4>
                    </div>
                    @if(!empty($task['lead']))
                        <p class="bns-expert-mehul__hw-task-lead">{!! bns_rich_text($task['lead']) !!}</p>
                    @endif
                    @if(!empty($task['sub_lead']))
                        <p class="bns-expert-mehul__hw-task-lead">{!! bns_rich_text($task['sub_lead']) !!}</p>
                    @endif
                    @if(!empty($task['items']))
                        <ul class="bns-expert-mehul__hw-task-list list-unstyled">
                            @foreach($task['items'] as $item)
                                <li>
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                    <span>{!! bns_rich_text($item) !!}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </article>
            @endforeach
        </div>

        @php($submission = $hw['submission'] ?? [])
        @if(!empty($submission['items']))
            <div class="bns-expert-mehul__hw-submission">
                @if(!empty($submission['title']))
                    <h4>{!! bns_rich_text($submission['title']) !!}</h4>
                @endif
                @if(!empty($submission['lead']))
                    <p>{!! bns_rich_text($submission['lead']) !!}</p>
                @endif
                <ul class="bns-expert-mehul__hw-task-list list-unstyled">
                    @foreach($submission['items'] as $item)
                        <li>
                            <i class="fas fa-folder-open" aria-hidden="true"></i>
                            <span>{!! bns_rich_text($item) !!}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php($thought = $hw['thought'] ?? [])
        @if(!empty($thought['quote']) || !empty($thought['lines']))
            <div class="bns-expert-mehul__hw-thought">
                @if(!empty($thought['label']))
                    <span class="bns-expert-mehul__creed-label">{{ $thought['label'] }}</span>
                @endif
                @if(!empty($thought['quote']))
                    <p class="bns-expert-mehul__hw-thought-quote">
                        <i class="fas fa-quote-left" aria-hidden="true"></i>
                        {!! bns_rich_text($thought['quote']) !!}
                    </p>
                @endif
                @if(!empty($thought['lines']))
                    <div class="bns-expert-mehul__hw-thought-lines">
                        @foreach($thought['lines'] as $line)
                            <p>{!! bns_rich_text($line) !!}</p>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </section>
@endif
