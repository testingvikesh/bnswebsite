<div class="bns-leader-profile__section">
    @if($credentialsHeading !== '')
        <span class="bns-leader-profile__section-label">{{ $credentialsHeading }}</span>
    @endif
    <div class="bns-leader-profile__credentials">
        @foreach($credentials as $credential)
            <span class="bns-leader-profile__credential">{{ $credential }}</span>
        @endforeach
    </div>
</div>
