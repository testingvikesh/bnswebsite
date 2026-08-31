/**
 * BNS Message page — single shared modal viewer.
 * Compatible with Bootstrap 5.0 (no getOrCreateInstance).
 */
(function () {
    'use strict';

    function getModalInstance(modalEl) {
        if (!window.bootstrap || !bootstrap.Modal || !modalEl) {
            return null;
        }

        if (typeof bootstrap.Modal.getOrCreateInstance === 'function') {
            return bootstrap.Modal.getOrCreateInstance(modalEl);
        }

        var existing = typeof bootstrap.Modal.getInstance === 'function'
            ? bootstrap.Modal.getInstance(modalEl)
            : null;

        return existing || new bootstrap.Modal(modalEl);
    }

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function escapeAttr(value) {
        return escapeHtml(value).replace(/'/g, '&#39;');
    }

    function renderPromo(promo) {
        if (!promo) {
            return '';
        }

        var journeys = (promo.journeys || []).map(function (item) {
            return '<li class="bns-promo__journey">' +
                '<span class="bns-promo__journey-icon" aria-hidden="true">' + escapeHtml(item.icon || '✨') + '</span>' +
                '<span class="bns-promo__journey-copy">' +
                    '<span class="bns-promo__journey-label">Learn the Journey from</span>' +
                    '<strong>' + escapeHtml(item.text || '') + '</strong>' +
                '</span>' +
                '</li>';
        }).join('');

        var sessions = (promo.sessions || []).map(function (session, index) {
            var period = index === 0 ? 'Evening' : 'Morning';
            return '<article class="bns-promo__session bns-promo__session--' + (index + 1) + '">' +
                '<div class="bns-promo__session-top">' +
                    '<span class="bns-promo__session-emoji" aria-hidden="true">' + escapeHtml(session.emoji || '📅') + '</span>' +
                    '<span class="bns-promo__session-chip">' + period + '</span>' +
                '</div>' +
                '<h4>' + escapeHtml(session.label || ('Session ' + (index + 1))) + '</h4>' +
                '<p class="bns-promo__session-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> ' + escapeHtml(session.date || '') + '</p>' +
                '<p class="bns-promo__session-time"><i class="fas fa-clock" aria-hidden="true"></i> ' + escapeHtml(session.time || '') + '</p>' +
                '</article>';
        }).join('');

        var venueLines = ((promo.venue && promo.venue.lines) || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        var partners = (promo.partners || []).map(function (partner) {
            return '<div class="bns-promo__partner">' +
                '<span>' + escapeHtml(partner.label || 'Partner') + '</span>' +
                '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                '</div>';
        }).join('');

        var tagline = (promo.tagline || []).map(function (line) {
            return '<span>' + escapeHtml(line) + '</span>';
        }).join('');

        var reel = promo.reel || {};
        var register = promo.register || {};
        var venue = promo.venue || {};

        return '<div class="bns-promo">' +
            '<div class="bns-promo__hero">' +
                '<span class="bns-promo__hero-badge">' + escapeHtml(promo.eyebrow || 'FREE Introduction Seminar') + '</span>' +
                '<h3>' + escapeHtml(promo.brand || 'Business Navachar School (BNS)') + '</h3>' +
                '<p class="bns-promo__hero-sub">' + escapeHtml(promo.hero_sub || 'Two free sessions · Limited seats · Registration required') + '</p>' +
                '<div class="bns-promo__hero-stats" aria-hidden="true">' +
                    '<span><i class="fas fa-calendar-check"></i> 2 Sessions</span>' +
                    '<span><i class="fas fa-map-marker-alt"></i> Santacruz West</span>' +
                    '<span><i class="fas fa-ticket-alt"></i> Free Entry</span>' +
                '</div>' +
            '</div>' +

            '<div class="bns-promo__card bns-promo__card--intro">' +
                '<p class="bns-promo__greeting"><strong>' + escapeHtml(promo.greeting || 'Dear Participant,') + '</strong></p>' +
                (promo.intro ? '<p>' + escapeHtml(promo.intro) + '</p>' : '') +
                ((promo.highlight_title || promo.highlight)
                    ? '<div class="bns-promo__alert">' +
                        (promo.highlight_title ? '<strong>' + escapeHtml(promo.highlight_title) + '</strong>' : '') +
                        (promo.highlight ? '<p>' + escapeHtml(promo.highlight) + '</p>' : '') +
                      '</div>'
                    : '') +
                (promo.opportunity ? '<p class="bns-promo__opportunity">' + escapeHtml(promo.opportunity) + '</p>' : '') +
            '</div>' +

            (journeys
                ? '<div class="bns-promo__card">' +
                    '<h4 class="bns-promo__card-title"><span>Learning Journeys</span></h4>' +
                    '<ul class="bns-promo__journeys">' + journeys + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-promo__actions-row">' +
                (reel.url
                    ? '<a class="bns-promo__action bns-promo__action--reel" href="' + escapeAttr(reel.url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fab fa-instagram" aria-hidden="true"></i>' +
                        '<span>' +
                            '<em>Watch</em>' +
                            escapeHtml(reel.label || 'Introduction Reel') +
                        '</span>' +
                      '</a>'
                    : '') +
                (register.url
                    ? '<a class="bns-promo__action bns-promo__action--register" href="' + escapeAttr(register.url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-clipboard-list" aria-hidden="true"></i>' +
                        '<span>' +
                            '<em>Register</em>' +
                            escapeHtml(register.label || 'Register Now') +
                        '</span>' +
                      '</a>'
                    : '') +
            '</div>' +

            (sessions
                ? '<div class="bns-promo__card bns-promo__card--sessions">' +
                    '<h4 class="bns-promo__card-title"><span>Choose Your Preferred Session</span></h4>' +
                    '<div class="bns-promo__sessions">' + sessions + '</div>' +
                  '</div>'
                : '') +

            (venue.title
                ? '<div class="bns-promo__card bns-promo__venue">' +
                    '<h4 class="bns-promo__card-title"><span>Venue &amp; GPS</span></h4>' +
                    '<strong class="bns-promo__venue-title">' + escapeHtml(venue.title) + '</strong>' +
                    (venueLines ? '<ul class="bns-promo__venue-lines">' + venueLines + '</ul>' : '') +
                    (venue.maps_url
                        ? '<a class="bns-promo__maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS Location' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            (partners
                ? '<div class="bns-promo__partners">' + partners + '</div>'
                : '') +

            (promo.website
                ? '<a class="bns-promo__website" href="' + escapeAttr(promo.website) + '" target="_blank" rel="noopener noreferrer">' +
                    '<i class="fas fa-globe" aria-hidden="true"></i> ' + escapeHtml(promo.website.replace(/^https?:\/\//, '')) +
                  '</a>'
                : '') +

            '<div class="bns-promo__footer">' +
                (promo.badge ? '<div class="bns-promo__pill">' + escapeHtml(promo.badge) + '</div>' : '') +
                (promo.closing ? '<p>' + escapeHtml(promo.closing) + '</p>' : '') +
                (tagline ? '<div class="bns-promo__tagline">' + tagline + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderList(items, className) {
        return (items || []).map(function (text) {
            return '<li class="' + className + '">' +
                '<span class="' + className + '-check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<span>' + escapeHtml(text) + '</span>' +
                '</li>';
        }).join('');
    }

    function renderAbout(about) {
        if (!about) {
            return '';
        }

        var why = renderList(about.why, 'bns-about__point');
        var who = (about.who || []).map(function (text) {
            return '<li class="bns-about__audience">' +
                '<span class="bns-about__audience-dot" aria-hidden="true"></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');
        var different = renderList(about.different, 'bns-about__point');
        var motto = (about.motto || []).map(function (item) {
            return '<div class="bns-about__motto-item">' +
                '<span aria-hidden="true">' + escapeHtml(item.icon || '✨') + '</span>' +
                '<strong>' + escapeHtml(item.text || '') + '</strong>' +
                '</div>';
        }).join('');

        return '<div class="bns-about">' +
            '<div class="bns-about__hero">' +
                '<span class="bns-about__hero-badge">' + escapeHtml(about.eyebrow || 'About BNS') + '</span>' +
                '<h3>' + escapeHtml(about.brand || 'Business Navachar School (BNS)') + '</h3>' +
                '<p class="bns-about__hero-sub">' + escapeHtml(about.headline || '') + '</p>' +
            '</div>' +

            '<div class="bns-about__card">' +
                '<h4 class="bns-about__card-title"><span>' + escapeHtml(about.what_title || 'What is BNS?') + '</span></h4>' +
                '<p>' + escapeHtml(about.what_intro || '') + '</p>' +
                '<p class="bns-about__focus">' + escapeHtml(about.what_focus || '') + '</p>' +
            '</div>' +

            (why
                ? '<div class="bns-about__card">' +
                    '<h4 class="bns-about__card-title"><span>' + escapeHtml(about.why_title || 'Why BNS?') + '</span></h4>' +
                    '<ul class="bns-about__points">' + why + '</ul>' +
                  '</div>'
                : '') +

            (who
                ? '<div class="bns-about__card bns-about__card--audience">' +
                    '<h4 class="bns-about__card-title"><span>' + escapeHtml(about.who_title || 'Who Can Join BNS?') + '</span></h4>' +
                    '<ul class="bns-about__audiences">' + who + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-about__mission-grid">' +
                '<div class="bns-about__mission-card bns-about__mission-card--mission">' +
                    '<span>Mission</span>' +
                    '<h4>' + escapeHtml(about.mission_title || 'Our Mission') + '</h4>' +
                    '<p>' + escapeHtml(about.mission || '') + '</p>' +
                '</div>' +
                '<div class="bns-about__mission-card bns-about__mission-card--vision">' +
                    '<span>Vision</span>' +
                    '<h4>' + escapeHtml(about.vision_title || 'Our Vision') + '</h4>' +
                    '<p>' + escapeHtml(about.vision || '') + '</p>' +
                '</div>' +
            '</div>' +

            (different
                ? '<div class="bns-about__card">' +
                    '<h4 class="bns-about__card-title"><span>' + escapeHtml(about.different_title || 'What Makes BNS Different?') + '</span></h4>' +
                    '<ul class="bns-about__points bns-about__points--two">' + different + '</ul>' +
                  '</div>'
                : '') +

            (motto
                ? '<div class="bns-about__footer">' +
                    '<h4>' + escapeHtml(about.motto_title || 'Our Motto') + '</h4>' +
                    '<div class="bns-about__motto">' + motto + '</div>' +
                  '</div>'
                : '') +

            '<div class="bns-about__actions">' +
                (about.about_url
                    ? '<a class="bns-about__action bns-about__action--about" href="' + escapeAttr(about.about_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-building" aria-hidden="true"></i> Read About BNS' +
                      '</a>'
                    : '') +
                (about.website
                    ? '<a class="bns-about__action bns-about__action--web" href="' + escapeAttr(about.website) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-globe" aria-hidden="true"></i> Visit Website' +
                      '</a>'
                    : '') +
            '</div>' +
        '</div>';
    }

    function renderVision(vision) {
        if (!vision) {
            return '';
        }

        var mission = (vision.mission || []).map(function (text, index) {
            return '<li class="bns-vision-msg__point">' +
                '<span class="bns-vision-msg__num" aria-hidden="true">' + String(index + 1).padStart(2, '0') + '</span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var motto = (vision.motto || []).map(function (item) {
            return '<div class="bns-vision-msg__motto-item">' +
                '<span aria-hidden="true">' + escapeHtml(item.icon || '✨') + '</span>' +
                '<strong>' + escapeHtml(item.text || '') + '</strong>' +
                '</div>';
        }).join('');

        return '<div class="bns-vision-msg">' +
            '<div class="bns-vision-msg__hero">' +
                '<span class="bns-vision-msg__hero-badge">' + escapeHtml(vision.eyebrow || 'Vision & Mission') + '</span>' +
                '<h3>' + escapeHtml(vision.headline || 'Our Vision & Mission') + '</h3>' +
                '<p class="bns-vision-msg__hero-sub">' + escapeHtml(vision.brand || 'Business Navachar School (BNS)') + '</p>' +
            '</div>' +

            '<div class="bns-vision-msg__card bns-vision-msg__card--vision">' +
                '<div class="bns-vision-msg__card-head">' +
                    '<span class="bns-vision-msg__icon" aria-hidden="true">🌍</span>' +
                    '<h4>' + escapeHtml(vision.vision_title || 'Our Vision') + '</h4>' +
                '</div>' +
                '<p>' + escapeHtml(vision.vision || '') + '</p>' +
                (vision.vision_support
                    ? '<div class="bns-vision-msg__support">' +
                        '<i class="fas fa-chalkboard-teacher" aria-hidden="true"></i>' +
                        '<span>' + escapeHtml(vision.vision_support) + '</span>' +
                      '</div>'
                    : '') +
            '</div>' +

            (mission
                ? '<div class="bns-vision-msg__card bns-vision-msg__card--mission">' +
                    '<div class="bns-vision-msg__card-head">' +
                        '<span class="bns-vision-msg__icon" aria-hidden="true">🎯</span>' +
                        '<h4>' + escapeHtml(vision.mission_title || 'Our Mission') + '</h4>' +
                    '</div>' +
                    '<ul class="bns-vision-msg__points">' + mission + '</ul>' +
                  '</div>'
                : '') +

            (motto
                ? '<div class="bns-vision-msg__footer">' +
                    '<div class="bns-vision-msg__motto">' + motto + '</div>' +
                  '</div>'
                : '') +

            '<div class="bns-vision-msg__actions">' +
                (vision.vision_url
                    ? '<a class="bns-vision-msg__action bns-vision-msg__action--vision" href="' + escapeAttr(vision.vision_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-eye" aria-hidden="true"></i> Our Vision' +
                      '</a>'
                    : '') +
                (vision.mission_url
                    ? '<a class="bns-vision-msg__action bns-vision-msg__action--mission" href="' + escapeAttr(vision.mission_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-bullseye" aria-hidden="true"></i> Our Mission' +
                      '</a>'
                    : '') +
                (vision.website
                    ? '<a class="bns-vision-msg__action bns-vision-msg__action--web" href="' + escapeAttr(vision.website) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-globe" aria-hidden="true"></i> Visit Website' +
                      '</a>'
                    : '') +
            '</div>' +
        '</div>';
    }

    function renderPitch(pitch) {
        if (!pitch) {
            return '';
        }

        var links = (pitch.links || []).map(function (link) {
            var tone = link.tone || 'web';
            return '<a class="bns-pitch__link bns-pitch__link--' + escapeAttr(tone) + '" href="' + escapeAttr(link.url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                '<span class="bns-pitch__link-icon" aria-hidden="true">' + escapeHtml(link.icon || '🔗') + '</span>' +
                '<span class="bns-pitch__link-copy">' +
                    '<strong>' + escapeHtml(link.label || 'Open link') + '</strong>' +
                    (link.hint ? '<em>' + escapeHtml(link.hint) + '</em>' : '') +
                '</span>' +
                '<span class="bns-pitch__link-arrow" aria-hidden="true"><i class="fas fa-arrow-right"></i></span>' +
                '</a>';
        }).join('');

        return '<div class="bns-pitch">' +
            '<div class="bns-pitch__hero">' +
                '<span class="bns-pitch__hero-badge">' + escapeHtml(pitch.eyebrow || 'Share Links') + '</span>' +
                '<h3>🌟 ' + escapeHtml(pitch.greeting || 'Dear Sir/Ma\'am,') + '</h3>' +
                '<p class="bns-pitch__hero-sub">' + escapeHtml(pitch.thanks || '') + '</p>' +
            '</div>' +

            '<div class="bns-pitch__card">' +
                '<p>' + escapeHtml(pitch.intro || '') + '</p>' +
            '</div>' +

            (links ? '<div class="bns-pitch__links">' + links + '</div>' : '') +

            '<div class="bns-pitch__card bns-pitch__card--closing">' +
                '<p>' + escapeHtml(pitch.closing || '') + '</p>' +
                (pitch.signoff ? '<p class="bns-pitch__signoff">' + escapeHtml(pitch.signoff) + '</p>' : '') +
                (pitch.brand ? '<strong class="bns-pitch__brand">' + escapeHtml(pitch.brand) + '</strong>' : '') +
            '</div>' +
        '</div>';
    }

    function renderReels(reels) {
        if (!reels) {
            return '';
        }

        var items = (reels.items || []).map(function (item, index) {
            var hasUrl = !!(item.url && String(item.url).trim());
            var inner =
                '<span class="bns-reels__num" aria-hidden="true">' + String(index + 1).padStart(2, '0') + '</span>' +
                '<span class="bns-reels__play" aria-hidden="true"><i class="fas fa-play"></i></span>' +
                '<span class="bns-reels__copy">' +
                    '<strong>' + escapeHtml(item.label || ('Introduction Reel ' + (index + 1))) + '</strong>' +
                    (item.hint ? '<em>' + escapeHtml(item.hint) + '</em>' : '') +
                '</span>' +
                '<span class="bns-reels__arrow" aria-hidden="true"><i class="fas fa-external-link-alt"></i></span>';

            if (hasUrl) {
                return '<a class="bns-reels__item" href="' + escapeAttr(item.url) + '" target="_blank" rel="noopener noreferrer">' + inner + '</a>';
            }

            return '<div class="bns-reels__item bns-reels__item--pending">' +
                inner.replace('fa-external-link-alt', 'fa-clock') +
                '</div>';
        }).join('');

        return '<div class="bns-reels">' +
            '<div class="bns-reels__hero">' +
                '<span class="bns-reels__hero-badge">' + escapeHtml(reels.eyebrow || 'Watch & Share') + '</span>' +
                '<h3>🎬 ' + escapeHtml(reels.headline || 'Introduction Reels') + '</h3>' +
                '<p class="bns-reels__hero-sub">' + escapeHtml(reels.intro || '') + '</p>' +
            '</div>' +

            (items ? '<div class="bns-reels__list">' + items + '</div>' : '') +

            '<div class="bns-reels__footer">' +
                (reels.closing ? '<p>' + escapeHtml(reels.closing) + '</p>' : '') +
                (reels.brand ? '<strong class="bns-reels__brand">' + escapeHtml(reels.brand) + '</strong>' : '') +
            '</div>' +
        '</div>';
    }

    function renderJourney(journey) {
        if (!journey) {
            return '';
        }

        var topics = (journey.topics || []).map(function (item) {
            return '<li class="bns-journey__topic">' +
                '<span class="bns-journey__topic-icon" aria-hidden="true">' + escapeHtml(item.icon || '✨') + '</span>' +
                '<strong>' + escapeHtml(item.text || '') + '</strong>' +
                '</li>';
        }).join('');

        var sessions = (journey.sessions || []).map(function (session, index) {
            var tone = session.tone || (index === 0 ? 'evening' : 'morning');
            return '<article class="bns-journey__session bns-journey__session--' + escapeAttr(tone) + '">' +
                '<span class="bns-journey__session-chip">' + escapeHtml(session.label || ('Session ' + (index + 1))) + '</span>' +
                '<p class="bns-journey__session-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> ' + escapeHtml(session.date || '') + '</p>' +
                '<p class="bns-journey__session-day"><i class="fas fa-sun" aria-hidden="true"></i> ' + escapeHtml(session.day || '') + '</p>' +
                '<p class="bns-journey__session-time"><i class="fas fa-clock" aria-hidden="true"></i> ' + escapeHtml(session.time || '') + '</p>' +
                '</article>';
        }).join('');

        var venue = journey.venue || {};
        var venueLines = (venue.lines || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        return '<div class="bns-journey">' +
            '<div class="bns-journey__hero">' +
                '<span class="bns-journey__hero-badge">' + escapeHtml(journey.eyebrow || 'Learning Roadmap') + '</span>' +
                '<h3>🚀 ' + escapeHtml(journey.headline || 'Business ABCD to IPO Journey') + '</h3>' +
                '<p class="bns-journey__hero-sub">' + escapeHtml(journey.hook || '') + '</p>' +
            '</div>' +

            '<div class="bns-journey__card">' +
                '<p>' + escapeHtml(journey.intro || '') + '</p>' +
            '</div>' +

            (topics
                ? '<div class="bns-journey__card">' +
                    '<h4 class="bns-journey__card-title"><span>' + escapeHtml(journey.learn_title || 'What Will You Learn?') + '</span></h4>' +
                    '<ul class="bns-journey__topics">' + topics + '</ul>' +
                  '</div>'
                : '') +

            (sessions
                ? '<div class="bns-journey__card bns-journey__card--sessions">' +
                    '<h4 class="bns-journey__card-title"><span>' + escapeHtml(journey.session_title || 'FREE Introduction Session') + '</span></h4>' +
                    '<div class="bns-journey__sessions">' + sessions + '</div>' +
                  '</div>'
                : '') +

            (venue.title || venueLines
                ? '<div class="bns-journey__card">' +
                    '<h4 class="bns-journey__card-title"><span>📍 ' + escapeHtml(venue.title || 'Venue Address') + '</span></h4>' +
                    (venueLines ? '<ul class="bns-journey__venue-lines">' + venueLines + '</ul>' : '') +
                    (venue.maps_url
                        ? '<a class="bns-journey__maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS Location' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            '<div class="bns-journey__actions">' +
                (journey.register_url
                    ? '<a class="bns-journey__action bns-journey__action--register" href="' + escapeAttr(journey.register_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-ticket-alt" aria-hidden="true"></i> ' + escapeHtml(journey.register_label || 'Book Your Seat Today') +
                      '</a>'
                    : '') +
                (journey.website
                    ? '<a class="bns-journey__action bns-journey__action--web" href="' + escapeAttr(journey.website) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-globe" aria-hidden="true"></i> Visit Website' +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-journey__footer">' +
                (journey.brand ? '<strong>' + escapeHtml(journey.brand) + '</strong>' : '') +
                (journey.tagline ? '<p>“' + escapeHtml(journey.tagline) + '”</p>' : '') +
            '</div>' +
        '</div>';
    }

    function renderBenefits(benefits) {
        if (!benefits) {
            return '';
        }

        var items = (benefits.items || []).map(function (text) {
            return '<li class="bns-benefits__item">' +
                '<span class="bns-benefits__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var sessionPoints = (benefits.session_points || []).map(function (text) {
            return '<li class="bns-benefits__point">' +
                '<span class="bns-benefits__bullet" aria-hidden="true"></span>' +
                '<span>' + escapeHtml(text) + '</span>' +
                '</li>';
        }).join('');

        return '<div class="bns-benefits">' +
            '<div class="bns-benefits__hero">' +
                '<span class="bns-benefits__hero-badge">' + escapeHtml(benefits.eyebrow || 'Why Join BNS') + '</span>' +
                '<h3>🌟 ' + escapeHtml(benefits.headline || 'Benefits of Joining BNS') + '</h3>' +
            '</div>' +

            '<div class="bns-benefits__card bns-benefits__card--why">' +
                '<p class="bns-benefits__question">' + escapeHtml(benefits.question || '') + '</p>' +
                '<p>' + escapeHtml(benefits.answer || '') + '</p>' +
            '</div>' +

            (items
                ? '<div class="bns-benefits__card">' +
                    '<h4 class="bns-benefits__card-title"><span>' + escapeHtml(benefits.list_title || 'You will get:') + '</span></h4>' +
                    '<ul class="bns-benefits__items">' + items + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-benefits__card bns-benefits__card--session">' +
                '<h4 class="bns-benefits__card-title"><span>' + escapeHtml(benefits.session_title || 'FREE Introduction Session') + '</span></h4>' +
                (benefits.session_intro ? '<p class="bns-benefits__session-intro">' + escapeHtml(benefits.session_intro) + '</p>' : '') +
                (sessionPoints ? '<ul class="bns-benefits__points">' + sessionPoints + '</ul>' : '') +
            '</div>' +

            '<div class="bns-benefits__cta-box">' +
                (benefits.cta_text ? '<p class="bns-benefits__cta-text">' + escapeHtml(benefits.cta_text) + '</p>' : '') +
                (benefits.welcome ? '<p>' + escapeHtml(benefits.welcome) + '</p>' : '') +
            '</div>' +

            '<div class="bns-benefits__actions">' +
                (benefits.register_url
                    ? '<a class="bns-benefits__action bns-benefits__action--register" href="' + escapeAttr(benefits.register_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-ticket-alt" aria-hidden="true"></i> ' + escapeHtml(benefits.register_label || 'Book FREE Session') +
                      '</a>'
                    : '') +
                (benefits.website
                    ? '<a class="bns-benefits__action bns-benefits__action--web" href="' + escapeAttr(benefits.website) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-globe" aria-hidden="true"></i> Visit Website' +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-benefits__footer">' +
                (benefits.brand ? '<strong>' + escapeHtml(benefits.brand) + '</strong>' : '') +
                (benefits.tagline ? '<p>“' + escapeHtml(benefits.tagline) + '”</p>' : '') +
            '</div>' +
        '</div>';
    }

    function renderHighlights(highlights) {
        if (!highlights) {
            return '';
        }

        var experience = (highlights.experience || []).map(function (text) {
            return '<li class="bns-highlights__item">' +
                '<span class="bns-highlights__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var next = (highlights.next || []).map(function (item) {
            return '<li class="bns-highlights__next-item">' +
                '<span class="bns-highlights__next-icon" aria-hidden="true">' + escapeHtml(item.icon || '✨') + '</span>' +
                '<strong>' + escapeHtml(item.text || '') + '</strong>' +
                '</li>';
        }).join('');

        return '<div class="bns-highlights">' +
            '<div class="bns-highlights__hero">' +
                '<span class="bns-highlights__hero-badge">' + escapeHtml(highlights.eyebrow || 'Past Seminar') + '</span>' +
                '<h3>📸 ' + escapeHtml(highlights.headline || 'Previous Seminar Highlights') + '</h3>' +
                '<p class="bns-highlights__hero-sub">' + escapeHtml(highlights.intro || '') + '</p>' +
            '</div>' +

            '<div class="bns-highlights__card">' +
                '<h4 class="bns-highlights__card-title"><span>' + escapeHtml(highlights.experience_title || 'Seminar Experience') + '</span></h4>' +
                (experience ? '<ul class="bns-highlights__items">' + experience + '</ul>' : '') +
                (highlights.feedback
                    ? '<div class="bns-highlights__quote">' +
                        '<i class="fas fa-quote-left" aria-hidden="true"></i>' +
                        '<p>' + escapeHtml(highlights.feedback) + '</p>' +
                      '</div>'
                    : '') +
            '</div>' +

            (next
                ? '<div class="bns-highlights__card bns-highlights__card--next">' +
                    '<h4 class="bns-highlights__card-title"><span>🚀 ' + escapeHtml(highlights.next_title || 'What\'s Next?') + '</span></h4>' +
                    (highlights.next_intro ? '<p class="bns-highlights__next-intro">' + escapeHtml(highlights.next_intro) + '</p>' : '') +
                    '<ul class="bns-highlights__next">' + next + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-highlights__cta-box">' +
                (highlights.opportunity ? '<p class="bns-highlights__opportunity">' + escapeHtml(highlights.opportunity) + '</p>' : '') +
                (highlights.cta_text ? '<p>' + escapeHtml(highlights.cta_text) + '</p>' : '') +
            '</div>' +

            '<div class="bns-highlights__actions">' +
                (highlights.register_url
                    ? '<a class="bns-highlights__action bns-highlights__action--register" href="' + escapeAttr(highlights.register_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-clipboard-list" aria-hidden="true"></i> ' + escapeHtml(highlights.register_label || 'Register Now') +
                      '</a>'
                    : '') +
                (highlights.events_url
                    ? '<a class="bns-highlights__action bns-highlights__action--events" href="' + escapeAttr(highlights.events_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-calendar-alt" aria-hidden="true"></i> View Events' +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-highlights__footer">' +
                (highlights.closing ? '<p>' + escapeHtml(highlights.closing) + '</p>' : '') +
                (highlights.brand ? '<strong>' + escapeHtml(highlights.brand) + '</strong>' : '') +
            '</div>' +
        '</div>';
    }

    function renderBring(bring) {
        if (!bring) {
            return '';
        }

        var items = (bring.items || []).map(function (item, index) {
            return '<li class="bns-bring__item">' +
                '<span class="bns-bring__num" aria-hidden="true">' + String(index + 1).padStart(2, '0') + '</span>' +
                '<span class="bns-bring__icon" aria-hidden="true">' + escapeHtml(item.icon || '✅') + '</span>' +
                '<span class="bns-bring__copy">' +
                    '<strong>' + escapeHtml(item.title || '') + '</strong>' +
                    '<em>' + escapeHtml(item.text || '') + '</em>' +
                '</span>' +
                '</li>';
        }).join('');

        var motto = (bring.motto || []).map(function (item) {
            return '<div class="bns-bring__motto-item">' +
                '<span aria-hidden="true">' + escapeHtml(item.icon || '✨') + '</span>' +
                '<strong>' + escapeHtml(item.text || '') + '</strong>' +
                '</div>';
        }).join('');

        return '<div class="bns-bring">' +
            '<div class="bns-bring__hero">' +
                '<span class="bns-bring__hero-badge">' + escapeHtml(bring.eyebrow || 'Seminar Checklist') + '</span>' +
                '<h3>🎒 ' + escapeHtml(bring.headline || 'What to Bring?') + '</h3>' +
                '<p class="bns-bring__hero-sub">' + escapeHtml(bring.intro || '') + '</p>' +
            '</div>' +

            (items ? '<ul class="bns-bring__list">' + items + '</ul>' : '') +

            (bring.report
                ? '<div class="bns-bring__alert">' +
                    '<i class="fas fa-clock" aria-hidden="true"></i>' +
                    '<div>' +
                        '<strong>Reporting Time</strong>' +
                        '<p>' + escapeHtml(bring.report) + '</p>' +
                    '</div>' +
                  '</div>'
                : '') +

            '<div class="bns-bring__footer">' +
                (bring.welcome ? '<p>' + escapeHtml(bring.welcome) + '</p>' : '') +
                (bring.brand ? '<strong class="bns-bring__brand">' + escapeHtml(bring.brand) + '</strong>' : '') +
                (motto ? '<div class="bns-bring__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderCountdown(countdown) {
        if (!countdown) {
            return '';
        }

        var venue = countdown.venue || {};
        var venueLines = (venue.lines || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        var learn = (countdown.learn || []).map(function (text) {
            return '<li class="bns-countdown__bring-item">' +
                '<span class="bns-countdown__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var highlights = (countdown.highlights || []).map(function (item) {
            return '<li class="bns-countdown__hl">' +
                '<span aria-hidden="true">' + escapeHtml(item.icon || '✨') + '</span>' +
                '<strong>' + escapeHtml(item.text || '') + '</strong>' +
                '</li>';
        }).join('');

        var bring = (countdown.bring || []).map(function (text) {
            return '<li class="bns-countdown__bring-item">' +
                '<span class="bns-countdown__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var reminders = (countdown.reminders || []).map(function (text) {
            return '<li class="bns-countdown__bring-item">' +
                '<span class="bns-countdown__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var during = (countdown.during || []).map(function (item) {
            return '<li class="bns-countdown__note">' +
                '<span class="bns-countdown__note-icon" aria-hidden="true">' + escapeHtml(item.icon || '📌') + '</span>' +
                '<span>' + escapeHtml(item.text || '') + '</span>' +
                '</li>';
        }).join('');

        var botFeatures = (countdown.bot_features || []).map(function (text) {
            return '<li>' + escapeHtml(text) + '</li>';
        }).join('');

        var motto = (countdown.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        var sessionsList = countdown.sessions || [];
        var richSessions = sessionsList.length && sessionsList.some(function (session) {
            return !!(session.reporting || session.tone);
        });
        var sessionsHtml = '';
        if (richSessions) {
            sessionsHtml = '<div class="bns-countdown__sessions">' +
                sessionsList.map(function (session) {
                    var tone = session.tone === 'blue' ? 'blue' : 'green';
                    return '<div class="bns-countdown__session bns-countdown__session--' + tone + '">' +
                        '<strong class="bns-countdown__session-label">' + escapeHtml(session.label || 'Session') + '</strong>' +
                        '<div class="bns-countdown__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(session.date || '') + '</strong></div></div>' +
                        '<div class="bns-countdown__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(session.time || '') + '</strong></div></div>' +
                        (session.reporting
                            ? '<div class="bns-countdown__event-row bns-countdown__event-row--accent bns-countdown__event-row--full"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(session.reporting) + '</strong></div></div>'
                            : '') +
                    '</div>';
                }).join('') +
            '</div>';
        } else if (sessionsList.length) {
            sessionsHtml = '<div class="bns-countdown__event">' +
                (countdown.sessions_title
                    ? '<div class="bns-countdown__event-row"><i class="fas fa-layer-group" aria-hidden="true"></i><div><span>Sessions</span><strong>' + escapeHtml(countdown.sessions_title) + '</strong></div></div>'
                    : '') +
                sessionsList.map(function (session, index) {
                    return '<div class="bns-countdown__event-row' + (index === 0 ? ' bns-countdown__event-row--accent' : '') + '">' +
                        '<i class="fas fa-calendar-check" aria-hidden="true"></i>' +
                        '<div>' +
                            '<span>' + escapeHtml(session.label || ('Session ' + (index + 1))) + '</span>' +
                            '<strong>' + escapeHtml(session.date || '') +
                                (session.time ? ' · ' + escapeHtml(session.time) : '') +
                            '</strong>' +
                        '</div>' +
                    '</div>';
                }).join('') +
            '</div>';
        } else if (countdown.date || countdown.time || countdown.report_time) {
            sessionsHtml = '<div class="bns-countdown__event">' +
                (countdown.date
                    ? '<div class="bns-countdown__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(countdown.date) + '</strong></div></div>'
                    : '') +
                (countdown.time
                    ? '<div class="bns-countdown__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(countdown.time) + '</strong></div></div>'
                    : '') +
                (countdown.report_time
                    ? '<div class="bns-countdown__event-row bns-countdown__event-row--accent"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(countdown.report_time) + '</strong></div></div>'
                    : '') +
            '</div>';
        }

        var partners = countdown.partners || [];
        var partnersHtml = partners.length
            ? '<div class="bns-countdown__partners">' +
                partners.map(function (partner) {
                    return '<div class="bns-countdown__partner">' +
                        '<span>' + escapeHtml(partner.label || '') + '</span>' +
                        '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                    '</div>';
                }).join('') +
              '</div>'
            : '';

        var hasConnect = !!(countdown.channel_url || countdown.bot_number || countdown.bot_url);

        return '<div class="bns-countdown">' +
            '<div class="bns-countdown__hero">' +
                '<span class="bns-countdown__hero-badge">' + escapeHtml(countdown.eyebrow || 'Countdown Alert') + '</span>' +
                '<div class="bns-countdown__days" aria-hidden="true">' + escapeHtml(countdown.days || '03') + '</div>' +
                '<h3>⏳ ' + escapeHtml(countdown.headline || 'Only 3 Days Left!') + '</h3>' +
                (countdown.tagline ? '<p class="bns-countdown__hero-sub">' + escapeHtml(countdown.tagline) + '</p>' : '') +
            '</div>' +

            ((countdown.thanks || countdown.reserved)
                ? '<div class="bns-countdown__card">' +
                    (countdown.thanks ? '<p>' + escapeHtml(countdown.thanks) + '</p>' : '') +
                    (countdown.reserved
                        ? '<div class="bns-countdown__reserved">' +
                            '<i class="fas fa-check-circle" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(countdown.reserved) + '</span>' +
                          '</div>'
                        : '') +
                  '</div>'
                : '') +

            sessionsHtml +

            (venue.title || venueLines
                ? '<div class="bns-countdown__card">' +
                    '<h4 class="bns-countdown__card-title"><span>📍 Venue</span></h4>' +
                    (venue.title ? '<strong class="bns-countdown__venue-title">' + escapeHtml(venue.title) + '</strong>' : '') +
                    (venueLines ? '<ul class="bns-countdown__venue-lines">' + venueLines + '</ul>' : '') +
                    (venue.maps_url
                        ? '<a class="bns-countdown__maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS Location' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            partnersHtml +

            (learn
                ? '<div class="bns-countdown__card">' +
                    '<h4 class="bns-countdown__card-title"><span>🎯 ' + escapeHtml(countdown.learn_title || 'What Will You Learn?') + '</span></h4>' +
                    '<ul class="bns-countdown__bring">' + learn + '</ul>' +
                  '</div>'
                : '') +

            (highlights
                ? '<div class="bns-countdown__card bns-countdown__card--hl">' +
                    '<h4 class="bns-countdown__card-title"><span>🌟 ' + escapeHtml(countdown.highlights_title || 'Previous Seminar Highlights') + '</span></h4>' +
                    '<ul class="bns-countdown__hls">' + highlights + '</ul>' +
                    (countdown.highlights_note ? '<p class="bns-countdown__hl-note">' + escapeHtml(countdown.highlights_note) + '</p>' : '') +
                    (countdown.highlights_cta ? '<strong class="bns-countdown__hl-cta">' + escapeHtml(countdown.highlights_cta) + '</strong>' : '') +
                  '</div>'
                : '') +

            (countdown.website
                ? '<a class="bns-countdown__web" href="' + escapeAttr(countdown.website) + '" target="_blank" rel="noopener noreferrer">' +
                    '<i class="fas fa-globe" aria-hidden="true"></i>' +
                    '<span>' +
                        '<em>Visit Our Website</em>' +
                        escapeHtml(countdown.website_intro || countdown.website.replace(/^https?:\/\//, '')) +
                    '</span>' +
                  '</a>'
                : '') +

            ((countdown.dress || countdown.dress_note)
                ? '<div class="bns-countdown__card">' +
                    '<h4 class="bns-countdown__card-title"><span>👔 ' + escapeHtml(countdown.dress_title || 'Dress Code') + '</span></h4>' +
                    (countdown.dress ? '<p><strong>' + escapeHtml(countdown.dress) + '</strong></p>' : '') +
                    (countdown.dress_note ? '<p>' + escapeHtml(countdown.dress_note) + '</p>' : '') +
                  '</div>'
                : '') +

            (bring
                ? '<div class="bns-countdown__card">' +
                    '<h4 class="bns-countdown__card-title"><span>🎒 ' + escapeHtml(countdown.bring_title || 'Please Bring') + '</span></h4>' +
                    '<ul class="bns-countdown__bring">' + bring + '</ul>' +
                  '</div>'
                : '') +

            (reminders
                ? '<div class="bns-countdown__card">' +
                    '<h4 class="bns-countdown__card-title"><span>⏰ ' + escapeHtml(countdown.reminder_title || 'Important Reminder') + '</span></h4>' +
                    '<ul class="bns-countdown__bring">' + reminders + '</ul>' +
                  '</div>'
                : '') +

            (during ? '<ul class="bns-countdown__notes">' +
                '<li class="bns-countdown__notes-title"><strong>📱 ' + escapeHtml(countdown.during_title || 'During the Seminar') + '</strong></li>' +
                during +
              '</ul>' : '') +

            (hasConnect
                ? '<div class="bns-countdown__connect">' +
                    (countdown.channel_url
                        ? '<a class="bns-countdown__channel" href="' + escapeAttr(countdown.channel_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(countdown.channel_title || 'Join WhatsApp Channel') + '</span>' +
                          '</a>'
                        : '') +
                    ((countdown.bot_number || countdown.bot_url)
                        ? '<div class="bns-countdown__bot">' +
                            '<span>' + escapeHtml(countdown.bot_title || 'Need Any Help?') + '</span>' +
                            (countdown.bot_intro ? '<em>' + escapeHtml(countdown.bot_intro) + '</em>' : '') +
                            (countdown.bot_number ? '<strong>' + escapeHtml(countdown.bot_number) + '</strong>' : '') +
                            (countdown.bot_list_title ? '<p class="bns-countdown__bot-list-title">' + escapeHtml(countdown.bot_list_title) + '</p>' : '') +
                            (botFeatures ? '<ul class="bns-countdown__bot-features">' + botFeatures + '</ul>' : '') +
                            (countdown.bot_url
                                ? '<a href="' + escapeAttr(countdown.bot_url) + '" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp" aria-hidden="true"></i> Message BOT</a>'
                                : '') +
                          '</div>'
                        : '') +
                  '</div>'
                : '') +

            ((countdown.invite || countdown.invite_intro)
                ? '<div class="bns-countdown__invite">' +
                    '<h4>🤝 ' + escapeHtml(countdown.invite_title || 'Invite Your Family & Friends') + '</h4>' +
                    (countdown.invite_intro ? '<p class="bns-countdown__invite-intro">' + escapeHtml(countdown.invite_intro) + '</p>' : '') +
                    (countdown.invite ? '<p>' + escapeHtml(countdown.invite) + '</p>' : '') +
                  '</div>'
                : '') +

            '<div class="bns-countdown__footer">' +
                (countdown.closing ? '<p class="bns-countdown__closing">' + escapeHtml(countdown.closing) + '</p>' : '') +
                (countdown.welcome ? '<p>' + escapeHtml(countdown.welcome) + '</p>' : '') +
                (countdown.brand ? '<strong class="bns-countdown__brand">' + escapeHtml(countdown.brand) + '</strong>' : '') +
                (motto ? '<div class="bns-countdown__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderCoach(coach) {
        if (!coach) {
            return '';
        }

        var lead = (coach.lead || []).map(function (text) {
            return '<p>' + escapeHtml(text) + '</p>';
        }).join('');

        var highlight = coach.highlight || null;
        var highlightHtml = highlight
            ? '<div class="bns-coach__highlight">' +
                '<i class="' + escapeAttr(highlight.icon || 'fas fa-check-circle') + '" aria-hidden="true"></i>' +
                '<div>' +
                    (highlight.title ? '<strong>' + escapeHtml(highlight.title) + '</strong>' : '') +
                    (highlight.text ? '<p>' + escapeHtml(highlight.text) + '</p>' : '') +
                '</div>' +
              '</div>'
            : '';

        var cards = (coach.cards || []).map(function (card) {
            var body = (card.body || []).map(function (text) {
                return '<p>' + escapeHtml(text) + '</p>';
            }).join('');

            var after = (card.after || []).map(function (text) {
                return '<p>' + escapeHtml(text) + '</p>';
            }).join('');

            var checks = (card.checks || []).map(function (text, index) {
                var icons = ['✅', '🌟', '🚀', '💼', '📈', '🏢', '💡', '🎯', '🤝', '🌐', '🛠', '📋'];
                return '<li class="bns-coach__journey">' +
                    '<span class="bns-coach__journey-icon" aria-hidden="true">' + escapeHtml(icons[index % icons.length]) + '</span>' +
                    '<span class="bns-coach__journey-copy">' +
                        '<span class="bns-coach__journey-label">' + escapeHtml(card.item_label || 'Business Coach Point') + '</span>' +
                        '<strong>' + escapeHtml(text) + '</strong>' +
                    '</span>' +
                    '</li>';
            }).join('');

            var blankChecks = (card.blank_checks || []).map(function (text) {
                return '<li class="bns-coach__journey">' +
                    '<span class="bns-coach__journey-icon" aria-hidden="true">✔️</span>' +
                    '<span class="bns-coach__journey-copy">' +
                        '<span class="bns-coach__journey-label">Key Takeaway</span>' +
                        '<strong>' + escapeHtml(text) + '</strong>' +
                    '</span>' +
                    '</li>';
            }).join('');

            var numbered = (card.numbered || []).map(function (text, index) {
                return '<li class="bns-coach__journey">' +
                    '<span class="bns-coach__journey-num">' + String(index + 1).padStart(2, '0') + '</span>' +
                    '<span class="bns-coach__journey-copy">' +
                        '<span class="bns-coach__journey-label">Discussion Point</span>' +
                        '<strong>' + escapeHtml(text) + '</strong>' +
                    '</span>' +
                    '</li>';
            }).join('');

            var fields = (card.fields || []).map(function (field) {
                return '<li class="bns-coach__journey">' +
                    '<span class="bns-coach__journey-fa" aria-hidden="true"><i class="' + escapeAttr(field.icon || 'fas fa-circle') + '"></i></span>' +
                    '<span class="bns-coach__journey-copy">' +
                        '<span class="bns-coach__journey-label">' + escapeHtml(field.label || '') + '</span>' +
                        '<strong>' + escapeHtml(field.value || '') + '</strong>' +
                    '</span>' +
                    '</li>';
            }).join('');

            return '<div class="bns-coach__card">' +
                '<h4><span class="bns-coach__card-title-wrap"><span class="bns-coach__card-emoji" aria-hidden="true">' + escapeHtml(card.emoji || '✨') + '</span> ' + escapeHtml(card.title || '') + '</span></h4>' +
                (card.highlight_inline ? '<p class="bns-coach__inline-highlight">' + escapeHtml(card.highlight_inline) + '</p>' : '') +
                body +
                (fields ? '<ul class="bns-coach__journeys">' + fields + '</ul>' : '') +
                (checks ? '<ul class="bns-coach__journeys">' + checks + '</ul>' : '') +
                (blankChecks ? '<ul class="bns-coach__journeys">' + blankChecks + '</ul>' : '') +
                (numbered ? '<ul class="bns-coach__journeys">' + numbered + '</ul>' : '') +
                after +
            '</div>';
        }).join('');

        var reels = (coach.reels || []).map(function (reel) {
            return '<a class="bns-coach__reel" href="' + escapeAttr(reel.url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                '<span aria-hidden="true">' + escapeHtml(reel.emoji || '🎬') + '</span>' +
                '<strong>' + escapeHtml(reel.label || 'Watch Reel') + '</strong>' +
                '<i class="fas fa-external-link-alt" aria-hidden="true"></i>' +
                '</a>';
        }).join('');

        var signers = (coach.signers || []).map(function (signer) {
            var phone = String(signer.phone || '');
            var phoneDigits = phone.replace(/[^\d]/g, '');
            var phoneHtml = phone
                ? (phoneDigits.length >= 10
                    ? '<a href="https://wa.me/' + encodeURIComponent(phoneDigits) + '" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp" aria-hidden="true"></i> ' + escapeHtml(phone) + '</a>'
                    : '<span><i class="fab fa-whatsapp" aria-hidden="true"></i> ' + escapeHtml(phone) + '</span>')
                : '';
            return '<div class="bns-coach__signer">' +
                '<strong>' + escapeHtml(signer.name || '') + '</strong>' +
                (signer.role ? '<span>' + escapeHtml(signer.role) + '</span>' : '') +
                (signer.org ? '<em>' + escapeHtml(signer.org) + '</em>' : '') +
                phoneHtml +
                '</div>';
        }).join('');

        var closing = (coach.closing || []).map(function (text) {
            return '<p>' + escapeHtml(text) + '</p>';
        }).join('');

        var motto = (coach.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-coach">' +
            '<div class="bns-coach__hero">' +
                '<span class="bns-coach__hero-badge">' + escapeHtml(coach.eyebrow || 'Business Coach') + '</span>' +
                '<div class="bns-coach__hero-icon" aria-hidden="true"><i class="' + escapeAttr(coach.hero_icon || 'fas fa-chalkboard-teacher') + '"></i></div>' +
                '<h3>🌟 ' + escapeHtml(coach.headline || 'Business Coach Message') + '</h3>' +
            '</div>' +

            '<div class="bns-coach__intro">' +
                (coach.greeting ? '<p class="bns-coach__greeting"><strong>' + escapeHtml(coach.greeting) + '</strong></p>' : '') +
                lead +
                highlightHtml +
            '</div>' +

            cards +

            (reels ? '<div class="bns-coach__reels">' + reels + '</div>' : '') +

            (coach.website
                ? '<a class="bns-coach__website" href="' + escapeAttr(coach.website) + '" target="_blank" rel="noopener noreferrer">' +
                    '<i class="fas fa-globe" aria-hidden="true"></i> ' + escapeHtml(String(coach.website).replace(/^https?:\/\//, '')) +
                  '</a>'
                : '') +

            '<div class="bns-coach__footer">' +
                closing +
                (signers ? '<div class="bns-coach__signers">' + signers + '</div>' : '') +
                (motto ? '<div class="bns-coach__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderConfirm(confirm) {
        if (!confirm) {
            return '';
        }

        var venue = confirm.venue || {};
        var venueLines = (venue.lines || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        var bring = (confirm.bring || []).map(function (text) {
            return '<li class="bns-confirm__bring-item">' +
                '<span class="bns-confirm__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var instructions = (confirm.instructions || []).map(function (item) {
            return '<li class="bns-confirm__note">' +
                '<span class="bns-confirm__note-icon" aria-hidden="true">' + escapeHtml(item.icon || '📌') + '</span>' +
                '<span>' + escapeHtml(item.text || '') + '</span>' +
                '</li>';
        }).join('');

        var motto = (confirm.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-confirm">' +
            '<div class="bns-confirm__hero">' +
                '<span class="bns-confirm__hero-badge">' + escapeHtml(confirm.eyebrow || 'Confirmed') + '</span>' +
                '<div class="bns-confirm__stamp" aria-hidden="true"><i class="fas fa-check"></i></div>' +
                '<h3>🌟 ' + escapeHtml(confirm.headline || 'Registration Confirmation') + '</h3>' +
            '</div>' +

            '<div class="bns-confirm__card">' +
                '<p class="bns-confirm__greeting"><strong>' + escapeHtml(confirm.greeting || 'Dear Participant,') + '</strong></p>' +
                '<p>' + escapeHtml(confirm.thanks || '') + '</p>' +
                '<div class="bns-confirm__status">' +
                    '<strong>' + escapeHtml(confirm.congrats || 'Congratulations!') + '</strong>' +
                    '<p>' + escapeHtml(confirm.status || '') + '</p>' +
                '</div>' +
                '<p>' + escapeHtml(confirm.welcome || '') + '</p>' +
            '</div>' +

            '<div class="bns-confirm__event">' +
                '<div class="bns-confirm__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(confirm.date || '') + '</strong></div></div>' +
                '<div class="bns-confirm__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(confirm.time || '') + '</strong></div></div>' +
                '<div class="bns-confirm__event-row bns-confirm__event-row--full"><i class="fas fa-hourglass-start" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(confirm.reporting || '') + '</strong></div></div>' +
            '</div>' +

            (venue.title || venueLines
                ? '<div class="bns-confirm__card">' +
                    '<h4 class="bns-confirm__card-title"><span>📍 Venue</span></h4>' +
                    (venue.title ? '<strong class="bns-confirm__venue-title">' + escapeHtml(venue.title) + '</strong>' : '') +
                    (venueLines ? '<ul class="bns-confirm__venue-lines">' + venueLines + '</ul>' : '') +
                    (venue.maps_url
                        ? '<a class="bns-confirm__maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS Location' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            (bring
                ? '<div class="bns-confirm__card">' +
                    '<h4 class="bns-confirm__card-title"><span>🎒 ' + escapeHtml(confirm.bring_title || 'Please Bring Along') + '</span></h4>' +
                    '<ul class="bns-confirm__bring">' + bring + '</ul>' +
                  '</div>'
                : '') +

            (instructions
                ? '<div class="bns-confirm__card">' +
                    '<h4 class="bns-confirm__card-title"><span>📢 ' + escapeHtml(confirm.instructions_title || 'Important Instructions') + '</span></h4>' +
                    '<ul class="bns-confirm__notes">' + instructions + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-confirm__invite">' +
                '<h4>' + escapeHtml(confirm.invite_title || 'Invite Your Family & Friends') + '</h4>' +
                '<p>' + escapeHtml(confirm.invite || '') + '</p>' +
            '</div>' +

            '<div class="bns-confirm__connect">' +
                '<div class="bns-confirm__bot">' +
                    '<span>' + escapeHtml(confirm.bot_title || 'BNS WhatsApp BOT') + '</span>' +
                    '<strong>' + escapeHtml(confirm.bot_number || '') + '</strong>' +
                    '<em>' + escapeHtml(confirm.bot_hint || '') + '</em>' +
                    (confirm.bot_url
                        ? '<a href="' + escapeAttr(confirm.bot_url) + '" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp" aria-hidden="true"></i> Message BOT</a>'
                        : '') +
                '</div>' +
                '<div class="bns-confirm__actions">' +
                    (confirm.channel_url
                        ? '<a class="bns-confirm__action bns-confirm__action--channel" href="' + escapeAttr(confirm.channel_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i> ' + escapeHtml(confirm.channel_title || 'WhatsApp Channel') +
                          '</a>'
                        : '') +
                    (confirm.website
                        ? '<a class="bns-confirm__action bns-confirm__action--web" href="' + escapeAttr(confirm.website) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-globe" aria-hidden="true"></i> Official Website' +
                          '</a>'
                        : '') +
                '</div>' +
            '</div>' +

            '<div class="bns-confirm__footer">' +
                (confirm.calendar_note ? '<p>' + escapeHtml(confirm.calendar_note) + '</p>' : '') +
                (confirm.help ? '<p class="bns-confirm__help">' + escapeHtml(confirm.help) + '</p>' : '') +
                (confirm.closing_thanks ? '<p class="bns-confirm__thanks">' + escapeHtml(confirm.closing_thanks) + '</p>' : '') +
                (confirm.closing ? '<p class="bns-confirm__closing">' + escapeHtml(confirm.closing) + '</p>' : '') +
                (motto ? '<div class="bns-confirm__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderThanks(thanks) {
        if (!thanks) {
            return '';
        }

        var insights = (thanks.insights || []).map(function (text) {
            return '<li class="bns-thanks__item">' +
                '<span class="bns-thanks__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var motto = (thanks.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-thanks">' +
            '<div class="bns-thanks__hero">' +
                '<span class="bns-thanks__hero-badge">' + escapeHtml(thanks.eyebrow || 'Gratitude') + '</span>' +
                '<h3>🌟 ' + escapeHtml(thanks.headline || 'Thank You') + '</h3>' +
            '</div>' +

            '<div class="bns-thanks__card">' +
                '<p class="bns-thanks__greeting"><strong>' + escapeHtml(thanks.greeting || 'Dear Participant,') + '</strong></p>' +
                '<p>' + escapeHtml(thanks.intro || '') + '</p>' +
                '<p>' + escapeHtml(thanks.appreciation || '') + '</p>' +
                '<div class="bns-thanks__growth">' +
                    '<i class="fas fa-seedling" aria-hidden="true"></i>' +
                    '<p>' + escapeHtml(thanks.growth || '') + '</p>' +
                '</div>' +
            '</div>' +

            (insights
                ? '<div class="bns-thanks__card">' +
                    '<h4 class="bns-thanks__card-title"><span>' + escapeHtml(thanks.insights_title || 'You will gain insights into:') + '</span></h4>' +
                    '<ul class="bns-thanks__items">' + insights + '</ul>' +
                  '</div>'
                : '') +

            (thanks.calendar_note
                ? '<div class="bns-thanks__alert">' +
                    '<i class="fas fa-calendar-check" aria-hidden="true"></i>' +
                    '<p>' + escapeHtml(thanks.calendar_note) + '</p>' +
                  '</div>'
                : '') +

            '<div class="bns-thanks__connect">' +
                '<div class="bns-thanks__bot">' +
                    '<span>' + escapeHtml(thanks.bot_title || 'BNS WhatsApp BOT') + '</span>' +
                    '<strong>' + escapeHtml(thanks.bot_number || '') + '</strong>' +
                    '<em>' + escapeHtml(thanks.bot_hint || '') + '</em>' +
                    (thanks.bot_url
                        ? '<a href="' + escapeAttr(thanks.bot_url) + '" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp" aria-hidden="true"></i> Message BOT</a>'
                        : '') +
                '</div>' +
                '<div class="bns-thanks__actions">' +
                    (thanks.channel_url
                        ? '<a class="bns-thanks__action bns-thanks__action--channel" href="' + escapeAttr(thanks.channel_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i> ' + escapeHtml(thanks.channel_title || 'WhatsApp Channel') +
                          '</a>'
                        : '') +
                    (thanks.website
                        ? '<a class="bns-thanks__action bns-thanks__action--web" href="' + escapeAttr(thanks.website) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-globe" aria-hidden="true"></i> Official Website' +
                          '</a>'
                        : '') +
                '</div>' +
            '</div>' +

            '<div class="bns-thanks__footer">' +
                (thanks.closing_thanks ? '<p class="bns-thanks__closing-thanks">' + escapeHtml(thanks.closing_thanks) + '</p>' : '') +
                (thanks.closing ? '<p>' + escapeHtml(thanks.closing) + '</p>' : '') +
                (thanks.see_you ? '<p class="bns-thanks__see-you">' + escapeHtml(thanks.see_you) + '</p>' : '') +
                (motto ? '<div class="bns-thanks__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderSaveDate(savedate) {
        if (!savedate) {
            return '';
        }

        var venue = savedate.venue || {};
        var venueLines = (venue.lines || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        var motto = (savedate.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        var sessions = savedate.sessions || [];
        var sessionsHtml = '';
        if (sessions.length) {
            sessionsHtml = '<div class="bns-savedate__sessions">' +
                sessions.map(function (session) {
                    var tone = session.tone === 'blue' ? 'blue' : 'green';
                    return '<div class="bns-savedate__session bns-savedate__session--' + tone + '">' +
                        '<strong class="bns-savedate__session-label">' + escapeHtml(session.label || 'Session') + '</strong>' +
                        '<div class="bns-savedate__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(session.date || '') + '</strong></div></div>' +
                        '<div class="bns-savedate__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Session Time</span><strong>' + escapeHtml(session.time || '') + '</strong></div></div>' +
                        '<div class="bns-savedate__event-row bns-savedate__event-row--full"><i class="fas fa-hourglass-start" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(session.reporting || '') + '</strong></div></div>' +
                    '</div>';
                }).join('') +
            '</div>';
        } else {
            sessionsHtml = '<div class="bns-savedate__event">' +
                '<div class="bns-savedate__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(savedate.date || '') + '</strong></div></div>' +
                '<div class="bns-savedate__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Time</span><strong>' + escapeHtml(savedate.time || '') + '</strong></div></div>' +
                '<div class="bns-savedate__event-row bns-savedate__event-row--full"><i class="fas fa-hourglass-start" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(savedate.reporting || '') + '</strong></div></div>' +
            '</div>';
        }

        var partners = savedate.partners || [];
        var partnersHtml = partners.length
            ? '<div class="bns-savedate__partners">' +
                partners.map(function (partner) {
                    return '<div class="bns-savedate__partner">' +
                        '<span>' + escapeHtml(partner.label || '') + '</span>' +
                        '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                    '</div>';
                }).join('') +
              '</div>'
            : '';

        return '<div class="bns-savedate">' +
            '<div class="bns-savedate__hero">' +
                '<span class="bns-savedate__hero-badge">' + escapeHtml(savedate.eyebrow || 'Calendar Invite') + '</span>' +
                '<h3>🌟 ' + escapeHtml(savedate.headline || 'Save the Date') + '</h3>' +
                '<p class="bns-savedate__mark">' + escapeHtml(savedate.mark || 'Mark Your Calendar!') + '</p>' +
            '</div>' +

            '<div class="bns-savedate__card">' +
                '<p>' + escapeHtml(savedate.intro || '') + '</p>' +
                '<p class="bns-savedate__invite">' + escapeHtml(savedate.invite || '') + '</p>' +
                '<p>' + escapeHtml(savedate.reserve || '') + '</p>' +
            '</div>' +

            sessionsHtml +

            (venue.title || venueLines
                ? '<div class="bns-savedate__card">' +
                    '<h4 class="bns-savedate__card-title"><span>📍 Venue</span></h4>' +
                    (venue.title ? '<strong class="bns-savedate__venue-title">' + escapeHtml(venue.title) + '</strong>' : '') +
                    (venueLines ? '<ul class="bns-savedate__venue-lines">' + venueLines + '</ul>' : '') +
                    (venue.maps_url
                        ? '<a class="bns-savedate__maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS Location' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            partnersHtml +

            '<div class="bns-savedate__note">' +
                '<i class="fas fa-thumbtack" aria-hidden="true"></i>' +
                '<p>' + escapeHtml(savedate.calendar_note || '') + '</p>' +
            '</div>' +

            '<div class="bns-savedate__actions">' +
                (venue.maps_url
                    ? '<a class="bns-savedate__action bns-savedate__action--maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS' +
                      '</a>'
                    : '') +
                (savedate.events_url
                    ? '<a class="bns-savedate__action bns-savedate__action--events" href="' + escapeAttr(savedate.events_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-calendar-check" aria-hidden="true"></i> View Events' +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-savedate__footer">' +
                (savedate.welcome ? '<p>' + escapeHtml(savedate.welcome) + '</p>' : '') +
                (savedate.see_you ? '<p class="bns-savedate__see-you">' + escapeHtml(savedate.see_you) + '</p>' : '') +
                (savedate.brand ? '<strong class="bns-savedate__brand">' + escapeHtml(savedate.brand) + '</strong>' : '') +
                (motto ? '<div class="bns-savedate__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderCalReminder(calreminder) {
        if (!calreminder) {
            return '';
        }

        var venue = calreminder.venue || {};
        var venueLines = (venue.lines || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        var motto = (calreminder.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        var sessions = calreminder.sessions || [];
        var sessionsHtml = '';
        if (sessions.length) {
            sessionsHtml = '<div class="bns-calreminder__sessions">' +
                sessions.map(function (session) {
                    var tone = session.tone === 'blue' ? 'blue' : 'green';
                    return '<div class="bns-calreminder__session bns-calreminder__session--' + tone + '">' +
                        '<strong class="bns-calreminder__session-label">' + escapeHtml(session.label || 'Session') + '</strong>' +
                        '<div class="bns-calreminder__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(session.date || '') + '</strong></div></div>' +
                        '<div class="bns-calreminder__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(session.time || '') + '</strong></div></div>' +
                        '<div class="bns-calreminder__event-row bns-calreminder__event-row--full"><i class="fas fa-hourglass-start" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(session.reporting || '') + '</strong></div></div>' +
                    '</div>';
                }).join('') +
            '</div>';
        } else {
            sessionsHtml = '<div class="bns-calreminder__event">' +
                '<div class="bns-calreminder__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(calreminder.date || '') + '</strong></div></div>' +
                '<div class="bns-calreminder__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(calreminder.time || '') + '</strong></div></div>' +
                '<div class="bns-calreminder__event-row bns-calreminder__event-row--full"><i class="fas fa-hourglass-start" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(calreminder.reporting || '') + '</strong></div></div>' +
            '</div>';
        }

        var partners = calreminder.partners || [];
        var partnersHtml = partners.length
            ? '<div class="bns-calreminder__partners">' +
                partners.map(function (partner) {
                    return '<div class="bns-calreminder__partner">' +
                        '<span>' + escapeHtml(partner.label || '') + '</span>' +
                        '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                    '</div>';
                }).join('') +
              '</div>'
            : '';

        return '<div class="bns-calreminder">' +
            '<div class="bns-calreminder__hero">' +
                '<span class="bns-calreminder__hero-badge">' + escapeHtml(calreminder.eyebrow || 'Reminder') + '</span>' +
                '<div class="bns-calreminder__bell" aria-hidden="true"><i class="fas fa-bell"></i></div>' +
                '<h3>📅 ' + escapeHtml(calreminder.headline || 'Calendar Reminder') + '</h3>' +
                '<p class="bns-calreminder__alert">' + escapeHtml(calreminder.alert || 'Don\'t Forget!') + '</p>' +
            '</div>' +

            '<div class="bns-calreminder__card">' +
                '<p>' + escapeHtml(calreminder.intro || '') + '</p>' +
                '<div class="bns-calreminder__action-box">' +
                    '<i class="fas fa-mobile-alt" aria-hidden="true"></i>' +
                    '<p>' + escapeHtml(calreminder.action || '') + '</p>' +
                '</div>' +
            '</div>' +

            sessionsHtml +

            (venue.title || venueLines
                ? '<div class="bns-calreminder__card">' +
                    '<h4 class="bns-calreminder__card-title"><span>📍 Venue</span></h4>' +
                    (venue.title ? '<strong class="bns-calreminder__venue-title">' + escapeHtml(venue.title) + '</strong>' : '') +
                    (venueLines ? '<ul class="bns-calreminder__venue-lines">' + venueLines + '</ul>' : '') +
                    (venue.maps_url
                        ? '<a class="bns-calreminder__maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS Location' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            partnersHtml +

            '<div class="bns-calreminder__tips">' +
                (calreminder.calendar_note
                    ? '<div class="bns-calreminder__tip"><i class="fas fa-thumbtack" aria-hidden="true"></i><p>' + escapeHtml(calreminder.calendar_note) + '</p></div>'
                    : '') +
                (calreminder.arrive_note
                    ? '<div class="bns-calreminder__tip"><i class="fas fa-clock" aria-hidden="true"></i><p>' + escapeHtml(calreminder.arrive_note) + '</p></div>'
                    : '') +
            '</div>' +

            '<div class="bns-calreminder__connect">' +
                '<div class="bns-calreminder__bot">' +
                    '<span>' + escapeHtml(calreminder.bot_title || 'BNS WhatsApp BOT') + '</span>' +
                    '<strong>' + escapeHtml(calreminder.bot_number || '') + '</strong>' +
                    (calreminder.bot_url
                        ? '<a href="' + escapeAttr(calreminder.bot_url) + '" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp" aria-hidden="true"></i> Message BOT</a>'
                        : '') +
                '</div>' +
                (calreminder.channel_url
                    ? '<a class="bns-calreminder__channel" href="' + escapeAttr(calreminder.channel_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fab fa-whatsapp" aria-hidden="true"></i> ' + escapeHtml(calreminder.channel_title || 'WhatsApp Channel') +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-calreminder__footer">' +
                (calreminder.welcome ? '<p>' + escapeHtml(calreminder.welcome) + '</p>' : '') +
                (calreminder.brand ? '<strong class="bns-calreminder__brand">' + escapeHtml(calreminder.brand) + '</strong>' : '') +
                (motto ? '<div class="bns-calreminder__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderWaChannel(wachannel) {
        if (!wachannel) {
            return '';
        }

        var benefits = (wachannel.benefits || []).map(function (text) {
            return '<li class="bns-wachannel__item">' +
                '<span class="bns-wachannel__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var botFeatures = (wachannel.bot_features || []).map(function (text) {
            return '<li class="bns-wachannel__bot-feature">' +
                '<span class="bns-wachannel__dot" aria-hidden="true"></span>' +
                '<span>' + escapeHtml(text) + '</span>' +
                '</li>';
        }).join('');

        var motto = (wachannel.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        var showBot = !!(wachannel.bot_number || wachannel.bot_url || wachannel.bot_intro);

        return '<div class="bns-wachannel">' +
            '<div class="bns-wachannel__hero">' +
                '<span class="bns-wachannel__hero-badge">' + escapeHtml(wachannel.eyebrow || 'Stay Connected') + '</span>' +
                '<div class="bns-wachannel__icon" aria-hidden="true"><i class="fab fa-whatsapp"></i></div>' +
                '<h3>📢 ' + escapeHtml(wachannel.headline || 'Join Our Official WhatsApp Channel') + '</h3>' +
                (wachannel.greeting ? '<p class="bns-wachannel__greeting">' + escapeHtml(wachannel.greeting) + '</p>' : '') +
                (wachannel.intro ? '<p class="bns-wachannel__hero-sub">' + escapeHtml(wachannel.intro) + '</p>' : '') +
            '</div>' +

            (benefits
                ? '<div class="bns-wachannel__card">' +
                    '<h4 class="bns-wachannel__card-title"><span>' + escapeHtml(wachannel.benefits_title || 'You will receive:') + '</span></h4>' +
                    '<ul class="bns-wachannel__items">' + benefits + '</ul>' +
                  '</div>'
                : '') +

            (wachannel.channel_url
                ? '<a class="bns-wachannel__join" href="' + escapeAttr(wachannel.channel_url) + '" target="_blank" rel="noopener noreferrer">' +
                    '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                    '<span>' +
                        '<em>Tap to Join</em>' +
                        escapeHtml(wachannel.channel_title || 'Official WhatsApp Channel') +
                    '</span>' +
                  '</a>'
                : '') +

            (wachannel.join_note
                ? '<div class="bns-wachannel__note"><p>' + escapeHtml(wachannel.join_note) + '</p></div>'
                : '') +

            (showBot
                ? '<div class="bns-wachannel__bot-card">' +
                    '<h4>' + escapeHtml(wachannel.bot_title || 'Need More Information?') + '</h4>' +
                    (wachannel.bot_intro ? '<p>' + escapeHtml(wachannel.bot_intro) + '</p>' : '') +
                    (wachannel.bot_number ? '<strong class="bns-wachannel__bot-number">' + escapeHtml(wachannel.bot_number) + '</strong>' : '') +
                    (wachannel.bot_list_title ? '<p class="bns-wachannel__bot-list-title">' + escapeHtml(wachannel.bot_list_title) + '</p>' : '') +
                    (botFeatures ? '<ul class="bns-wachannel__bot-features">' + botFeatures + '</ul>' : '') +
                    (wachannel.bot_url
                        ? '<a class="bns-wachannel__bot-btn" href="' + escapeAttr(wachannel.bot_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i> Message BOT' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            (wachannel.website
                ? '<a class="bns-wachannel__web" href="' + escapeAttr(wachannel.website) + '" target="_blank" rel="noopener noreferrer">' +
                    '<i class="fas fa-globe" aria-hidden="true"></i> ' + escapeHtml(wachannel.website.replace(/^https?:\/\//, '')) +
                  '</a>'
                : '') +

            '<div class="bns-wachannel__footer">' +
                (wachannel.closing ? '<p>' + escapeHtml(wachannel.closing) + '</p>' : '') +
                (motto ? '<div class="bns-wachannel__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderFounderWelcome(founderwelcome) {
        if (!founderwelcome) {
            return '';
        }

        function checkItems(items) {
            return (items || []).map(function (text) {
                return '<li class="bns-founderwelcome__item">' +
                    '<span class="bns-founderwelcome__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                    '<strong>' + escapeHtml(text) + '</strong>' +
                    '</li>';
            }).join('');
        }

        var promise = checkItems(founderwelcome.promise);
        var grow = checkItems(founderwelcome.grow);

        var successLines = (founderwelcome.success_lines || []).map(function (text) {
            return '<p class="bns-founderwelcome__success-line">' + escapeHtml(text) + '</p>';
        }).join('');

        var motto = (founderwelcome.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-founderwelcome">' +
            '<div class="bns-founderwelcome__hero">' +
                '<span class="bns-founderwelcome__hero-badge">' + escapeHtml(founderwelcome.eyebrow || 'From the Founder') + '</span>' +
                '<div class="bns-founderwelcome__hero-icon" aria-hidden="true"><i class="fas fa-seedling"></i></div>' +
                '<h3>💐 ' + escapeHtml(founderwelcome.headline || 'Founder\'s Welcome Message') + '</h3>' +
                (founderwelcome.greeting ? '<p class="bns-founderwelcome__greeting">' + escapeHtml(founderwelcome.greeting) + '</p>' : '') +
                (founderwelcome.welcome ? '<p class="bns-founderwelcome__lead">' + escapeHtml(founderwelcome.welcome) + '</p>' : '') +
                (founderwelcome.thanks ? '<p class="bns-founderwelcome__hero-sub">' + escapeHtml(founderwelcome.thanks) + '</p>' : '') +
            '</div>' +

            '<div class="bns-founderwelcome__belief">' +
                (founderwelcome.belief ? '<p>' + escapeHtml(founderwelcome.belief) + '</p>' : '') +
                (founderwelcome.mission ? '<p>' + escapeHtml(founderwelcome.mission) + '</p>' : '') +
            '</div>' +

            '<div class="bns-founderwelcome__card">' +
                '<h4>🌟 ' + escapeHtml(founderwelcome.promise_title || 'My Promise to You') + '</h4>' +
                (founderwelcome.promise_intro ? '<p class="bns-founderwelcome__intro">' + escapeHtml(founderwelcome.promise_intro) + '</p>' : '') +
                (promise ? '<ul class="bns-founderwelcome__list">' + promise + '</ul>' : '') +
                (founderwelcome.promise_focus ? '<p class="bns-founderwelcome__focus">' + escapeHtml(founderwelcome.promise_focus) + '</p>' : '') +
            '</div>' +

            '<div class="bns-founderwelcome__success">' +
                '<h4>🚀 ' + escapeHtml(founderwelcome.success_title || 'Your Success is Our Mission') + '</h4>' +
                successLines +
                (founderwelcome.success_result ? '<p class="bns-founderwelcome__result">' + escapeHtml(founderwelcome.success_result) + '</p>' : '') +
                (founderwelcome.formula_label ? '<em>' + escapeHtml(founderwelcome.formula_label) + '</em>' : '') +
                (founderwelcome.formula ? '<strong class="bns-founderwelcome__formula">' + escapeHtml(founderwelcome.formula) + '</strong>' : '') +
            '</div>' +

            '<div class="bns-founderwelcome__card">' +
                '<h4>🤝 ' + escapeHtml(founderwelcome.grow_title || 'Let\'s Grow Together') + '</h4>' +
                (founderwelcome.grow_intro ? '<p class="bns-founderwelcome__intro">' + escapeHtml(founderwelcome.grow_intro) + '</p>' : '') +
                (grow ? '<ul class="bns-founderwelcome__list">' + grow + '</ul>' : '') +
                (founderwelcome.commitment ? '<p class="bns-founderwelcome__focus">' + escapeHtml(founderwelcome.commitment) + '</p>' : '') +
            '</div>' +

            '<div class="bns-founderwelcome__final">' +
                (founderwelcome.again_title ? '<em class="bns-founderwelcome__again">' + escapeHtml(founderwelcome.again_title) + '</em>' : '') +
                (founderwelcome.again_thanks ? '<p>' + escapeHtml(founderwelcome.again_thanks) + '</p>' : '') +
                (founderwelcome.meet ? '<p>' + escapeHtml(founderwelcome.meet) + '</p>' : '') +
                (founderwelcome.wish ? '<p>' + escapeHtml(founderwelcome.wish) + '</p>' : '') +
                (founderwelcome.regards ? '<span class="bns-founderwelcome__regards">' + escapeHtml(founderwelcome.regards) + '</span>' : '') +
                '<div class="bns-founderwelcome__signature">' +
                    (founderwelcome.name ? '<strong>' + escapeHtml(founderwelcome.name) + '</strong>' : '') +
                    (founderwelcome.role ? '<span>' + escapeHtml(founderwelcome.role) + '</span>' : '') +
                    (founderwelcome.brand ? '<span>' + escapeHtml(founderwelcome.brand) + '</span>' : '') +
                '</div>' +
                (motto ? '<div class="bns-founderwelcome__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderBnsFamily(bnsfamily) {
        if (!bnsfamily) {
            return '';
        }

        var journey = (bnsfamily.journey || []).map(function (item) {
            return '<li class="bns-bnsfamily__journey-item">' +
                '<span aria-hidden="true">' + escapeHtml(item.icon || '🌟') + '</span>' +
                '<strong>' + escapeHtml(item.text || '') + '</strong>' +
                '</li>';
        }).join('');

        var next = (bnsfamily.next || []).map(function (text) {
            return '<li class="bns-bnsfamily__item">' +
                '<span class="bns-bnsfamily__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var instructions = (bnsfamily.instructions || []).map(function (text) {
            return '<li class="bns-bnsfamily__item">' +
                '<span class="bns-bnsfamily__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<span>' + escapeHtml(text) + '</span>' +
                '</li>';
        }).join('');

        var motto = (bnsfamily.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-bnsfamily">' +
            '<div class="bns-bnsfamily__hero">' +
                '<span class="bns-bnsfamily__hero-badge">' + escapeHtml(bnsfamily.eyebrow || 'Admission Confirmed') + '</span>' +
                '<div class="bns-bnsfamily__hero-icon" aria-hidden="true"><i class="fas fa-home"></i></div>' +
                '<h3>🎉 ' + escapeHtml(bnsfamily.headline || 'Welcome to the BNS Family') + '</h3>' +
                (bnsfamily.greeting ? '<p class="bns-bnsfamily__greeting">' + escapeHtml(bnsfamily.greeting) + '</p>' : '') +
                (bnsfamily.congrats ? '<strong class="bns-bnsfamily__congrats">' + escapeHtml(bnsfamily.congrats) + '</strong>' : '') +
                (bnsfamily.confirmed ? '<p class="bns-bnsfamily__confirmed">' + escapeHtml(bnsfamily.confirmed) + '</p>' : '') +
                (bnsfamily.delighted ? '<p class="bns-bnsfamily__hero-sub">' + escapeHtml(bnsfamily.delighted) + '</p>' : '') +
            '</div>' +

            '<div class="bns-bnsfamily__card">' +
                '<h4>🌟 ' + escapeHtml(bnsfamily.journey_title || 'Your Journey Begins Today') + '</h4>' +
                (bnsfamily.journey_intro ? '<p class="bns-bnsfamily__intro">' + escapeHtml(bnsfamily.journey_intro) + '</p>' : '') +
                (journey ? '<ul class="bns-bnsfamily__journey">' + journey + '</ul>' : '') +
            '</div>' +

            '<div class="bns-bnsfamily__card bns-bnsfamily__card--next">' +
                '<h4>📋 ' + escapeHtml(bnsfamily.next_title || 'What Happens Next?') + '</h4>' +
                (bnsfamily.next_intro ? '<p class="bns-bnsfamily__intro">' + escapeHtml(bnsfamily.next_intro) + '</p>' : '') +
                (next ? '<ul class="bns-bnsfamily__list">' + next + '</ul>' : '') +
            '</div>' +

            '<div class="bns-bnsfamily__connect">' +
                '<h4>📲 ' + escapeHtml(bnsfamily.connect_title || 'Stay Connected') + '</h4>' +
                '<div class="bns-bnsfamily__connect-grid">' +
                    '<a class="bns-bnsfamily__connect-btn bns-bnsfamily__connect-btn--web" href="' + escapeAttr(bnsfamily.web_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-globe" aria-hidden="true"></i>' +
                        '<span><em>' + escapeHtml(bnsfamily.web_label || 'Official Website') + '</em><strong>Visit Website</strong></span>' +
                    '</a>' +
                    '<a class="bns-bnsfamily__connect-btn bns-bnsfamily__connect-btn--bot" href="' + escapeAttr(bnsfamily.bot_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                        '<span>' +
                            '<em>' + escapeHtml(bnsfamily.bot_label || 'BNS WhatsApp BOT') + '</em>' +
                            '<strong>' + escapeHtml(bnsfamily.bot_number || '') + '</strong>' +
                            (bnsfamily.bot_hint ? '<small>' + escapeHtml(bnsfamily.bot_hint) + '</small>' : '') +
                        '</span>' +
                    '</a>' +
                    '<a class="bns-bnsfamily__connect-btn bns-bnsfamily__connect-btn--channel" href="' + escapeAttr(bnsfamily.channel_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                        '<span><em>' + escapeHtml(bnsfamily.channel_label || 'WhatsApp Channel') + '</em><strong>Join Channel</strong></span>' +
                    '</a>' +
                '</div>' +
            '</div>' +

            '<div class="bns-bnsfamily__card">' +
                '<h4>📌 ' + escapeHtml(bnsfamily.instructions_title || 'Important Instructions') + '</h4>' +
                (instructions ? '<ul class="bns-bnsfamily__list">' + instructions + '</ul>' : '') +
            '</div>' +

            (bnsfamily.commit
                ? '<div class="bns-bnsfamily__commit">' +
                    '<h4>🌱 ' + escapeHtml(bnsfamily.commit_title || 'Our Commitment') + '</h4>' +
                    '<p>' + escapeHtml(bnsfamily.commit) + '</p>' +
                  '</div>'
                : '') +

            '<div class="bns-bnsfamily__final">' +
                (bnsfamily.again_title ? '<em class="bns-bnsfamily__again">' + escapeHtml(bnsfamily.again_title) + '</em>' : '') +
                (bnsfamily.family ? '<strong class="bns-bnsfamily__family">' + escapeHtml(bnsfamily.family) + '</strong>' : '') +
                (bnsfamily.excited ? '<p>' + escapeHtml(bnsfamily.excited) + '</p>' : '') +
                (bnsfamily.together ? '<p class="bns-bnsfamily__together">' + escapeHtml(bnsfamily.together) + '</p>' : '') +
                (motto ? '<div class="bns-bnsfamily__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderFaq(faq) {
        if (!faq) {
            return '';
        }

        var items = (faq.items || []).map(function (item, index) {
            return '<div class="bns-faq-msg__item">' +
                '<div class="bns-faq-msg__q">' +
                    '<span class="bns-faq-msg__num" aria-hidden="true">' + String(index + 1).padStart(2, '0') + '</span>' +
                    '<strong>' + escapeHtml(item.q || '') + '</strong>' +
                '</div>' +
                '<p class="bns-faq-msg__a">' + escapeHtml(item.a || '') + '</p>' +
            '</div>';
        }).join('');

        return '<div class="bns-faq-msg">' +
            '<div class="bns-faq-msg__hero">' +
                '<span class="bns-faq-msg__hero-badge">' + escapeHtml(faq.eyebrow || 'Quick Answers') + '</span>' +
                '<div class="bns-faq-msg__hero-icon" aria-hidden="true"><i class="fas fa-question-circle"></i></div>' +
                '<h3>❓ ' + escapeHtml(faq.headline || 'Frequently Asked Questions (FAQ)') + '</h3>' +
            '</div>' +

            (items ? '<div class="bns-faq-msg__list">' + items + '</div>' : '') +

            '<div class="bns-faq-msg__assist">' +
                '<h4>' + escapeHtml(faq.assist_title || 'Still have questions?') + '</h4>' +
                '<a class="bns-faq-msg__bot" href="' + escapeAttr(faq.bot_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                    '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                    '<span>' +
                        '<em>' + escapeHtml(faq.bot_label || 'WhatsApp BOT') + '</em>' +
                        '<strong>' + escapeHtml(faq.bot_number || '') + '</strong>' +
                        (faq.bot_hint ? '<small>' + escapeHtml(faq.bot_hint) + '</small>' : '') +
                    '</span>' +
                '</a>' +
            '</div>' +
        '</div>';
    }

    function renderFirstBatch(firstbatch) {
        if (!firstbatch) {
            return '';
        }

        var journey = (firstbatch.journey || []).map(function (text) {
            return '<li class="bns-firstbatch__item">' +
                '<span class="bns-firstbatch__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var motto = (firstbatch.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-firstbatch">' +
            '<div class="bns-firstbatch__hero">' +
                '<span class="bns-firstbatch__hero-badge">' + escapeHtml(firstbatch.eyebrow || 'Founding Batch') + '</span>' +
                '<div class="bns-firstbatch__hero-icon" aria-hidden="true"><i class="fas fa-trophy"></i></div>' +
                '<h3>🎉 ' + escapeHtml(firstbatch.headline || 'Welcome to the First Batch') + '</h3>' +
                (firstbatch.greeting ? '<p class="bns-firstbatch__greeting">' + escapeHtml(firstbatch.greeting) + '</p>' : '') +
                (firstbatch.congrats ? '<p class="bns-firstbatch__congrats">' + escapeHtml(firstbatch.congrats) + '</p>' : '') +
                (firstbatch.community ? '<p class="bns-firstbatch__hero-sub">' + escapeHtml(firstbatch.community) + '</p>' : '') +
                (firstbatch.founding ? '<p class="bns-firstbatch__hero-sub">' + escapeHtml(firstbatch.founding) + '</p>' : '') +
            '</div>' +

            (journey
                ? '<div class="bns-firstbatch__card">' +
                    '<h4>🌟 ' + escapeHtml(firstbatch.journey_title || 'Your Journey Begins Here') + '</h4>' +
                    '<ul class="bns-firstbatch__list">' + journey + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-firstbatch__inspire">' +
                (firstbatch.commitment ? '<p>' + escapeHtml(firstbatch.commitment) + '</p>' : '') +
                (firstbatch.together ? '<strong>' + escapeHtml(firstbatch.together) + '</strong>' : '') +
            '</div>' +

            '<div class="bns-firstbatch__final">' +
                (firstbatch.family ? '<strong class="bns-firstbatch__family">' + escapeHtml(firstbatch.family) + '</strong>' : '') +
                (motto ? '<div class="bns-firstbatch__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderPayNow(paynow) {
        if (!paynow) {
            return '';
        }

        var perks = (paynow.perks || []).map(function (text) {
            return '<li class="bns-paynow__item">' +
                '<span class="bns-paynow__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var motto = (paynow.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-paynow">' +
            '<div class="bns-paynow__hero">' +
                '<span class="bns-paynow__hero-badge">' + escapeHtml(paynow.eyebrow || 'Secure Checkout') + '</span>' +
                '<div class="bns-paynow__hero-icon" aria-hidden="true"><i class="fas fa-credit-card"></i></div>' +
                '<h3>💳 ' + escapeHtml(paynow.headline || 'Complete Your Admission Payment') + '</h3>' +
                (paynow.greeting ? '<p class="bns-paynow__greeting">' + escapeHtml(paynow.greeting) + '</p>' : '') +
                (paynow.thanks ? '<p class="bns-paynow__hero-sub">' + escapeHtml(paynow.thanks) + '</p>' : '') +
                (paynow.intro ? '<p class="bns-paynow__hero-sub">' + escapeHtml(paynow.intro) + '</p>' : '') +
            '</div>' +

            '<div class="bns-paynow__pay">' +
                '<div class="bns-paynow__pay-icon" aria-hidden="true"><i class="fas fa-lock"></i></div>' +
                '<h4>' + escapeHtml(paynow.pay_title || 'Payment Link') + '</h4>' +
                (paynow.pay_url
                    ? '<a class="bns-paynow__cta" href="' + escapeAttr(paynow.pay_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-credit-card" aria-hidden="true"></i> ' + escapeHtml(paynow.pay_label || 'Pay Now') +
                      '</a>'
                    : '') +
            '</div>' +

            (perks ? '<ul class="bns-paynow__list">' + perks + '</ul>' : '') +

            '<div class="bns-paynow__assist">' +
                '<h4>❓ ' + escapeHtml(paynow.assist_title || 'Need Assistance?') + '</h4>' +
                '<a class="bns-paynow__bot" href="' + escapeAttr(paynow.bot_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                    '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                    '<span>' +
                        '<em>' + escapeHtml(paynow.bot_label || 'WhatsApp BOT') + '</em>' +
                        '<strong>' + escapeHtml(paynow.bot_number || '') + '</strong>' +
                        (paynow.bot_hint ? '<small>' + escapeHtml(paynow.bot_hint) + '</small>' : '') +
                    '</span>' +
                '</a>' +
            '</div>' +

            '<div class="bns-paynow__final">' +
                (paynow.urgency ? '<strong class="bns-paynow__urgency">' + escapeHtml(paynow.urgency) + '</strong>' : '') +
                (paynow.family ? '<strong class="bns-paynow__family">' + escapeHtml(paynow.family) + '</strong>' : '') +
                (motto ? '<div class="bns-paynow__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderAdmitReminder(admitreminder) {
        if (!admitreminder) {
            return '';
        }

        var benefits = (admitreminder.benefits || []).map(function (text) {
            return '<li class="bns-admitreminder__item">' +
                '<span class="bns-admitreminder__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var motto = (admitreminder.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-admitreminder">' +
            '<div class="bns-admitreminder__hero">' +
                '<span class="bns-admitreminder__hero-badge">' + escapeHtml(admitreminder.eyebrow || 'Action Needed') + '</span>' +
                '<div class="bns-admitreminder__hero-icon" aria-hidden="true"><i class="fas fa-hourglass-half"></i></div>' +
                '<h3>⏳ ' + escapeHtml(admitreminder.headline || 'Admission Reminder') + '</h3>' +
                (admitreminder.greeting ? '<p class="bns-admitreminder__greeting">' + escapeHtml(admitreminder.greeting) + '</p>' : '') +
                (admitreminder.thanks ? '<p class="bns-admitreminder__hero-sub">' + escapeHtml(admitreminder.thanks) + '</p>' : '') +
                (admitreminder.request ? '<p class="bns-admitreminder__hero-sub">' + escapeHtml(admitreminder.request) + '</p>' : '') +
                (admitreminder.opportunity ? '<p class="bns-admitreminder__hero-sub">' + escapeHtml(admitreminder.opportunity) + '</p>' : '') +
            '</div>' +

            (benefits
                ? '<div class="bns-admitreminder__card">' +
                    '<h4>🎯 ' + escapeHtml(admitreminder.benefits_title || 'Admission Benefits') + '</h4>' +
                    '<ul class="bns-admitreminder__list">' + benefits + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-admitreminder__actions">' +
                (admitreminder.complete ? '<h4>🎓 ' + escapeHtml(admitreminder.complete) + '</h4>' : '') +
                '<div class="bns-admitreminder__btns">' +
                    (admitreminder.portal_url
                        ? '<a class="bns-admitreminder__btn bns-admitreminder__btn--portal" href="' + escapeAttr(admitreminder.portal_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-user-graduate" aria-hidden="true"></i> ' + escapeHtml(admitreminder.portal_label || 'Admission Portal') +
                          '</a>'
                        : '') +
                    (admitreminder.pay_url
                        ? '<a class="bns-admitreminder__btn bns-admitreminder__btn--pay" href="' + escapeAttr(admitreminder.pay_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-credit-card" aria-hidden="true"></i> ' + escapeHtml(admitreminder.pay_label || 'Pay Online') +
                          '</a>'
                        : '') +
                '</div>' +
            '</div>' +

            '<div class="bns-admitreminder__assist">' +
                '<h4>❓ ' + escapeHtml(admitreminder.assist_title || 'Need Assistance?') + '</h4>' +
                '<a class="bns-admitreminder__bot" href="' + escapeAttr(admitreminder.bot_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                    '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                    '<span>' +
                        '<em>' + escapeHtml(admitreminder.bot_label || 'WhatsApp BOT') + '</em>' +
                        '<strong>' + escapeHtml(admitreminder.bot_number || '') + '</strong>' +
                        (admitreminder.bot_hint ? '<small>' + escapeHtml(admitreminder.bot_hint) + '</small>' : '') +
                    '</span>' +
                '</a>' +
            '</div>' +

            '<div class="bns-admitreminder__final">' +
                (admitreminder.urgency ? '<strong class="bns-admitreminder__urgency">' + escapeHtml(admitreminder.urgency) + '</strong>' : '') +
                (motto ? '<div class="bns-admitreminder__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderSyllabus(syllabus) {
        if (!syllabus) {
            return '';
        }

        var highlights = (syllabus.highlights || []).map(function (text) {
            return '<li class="bns-syllabus-msg__item">' +
                '<span class="bns-syllabus-msg__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var motto = (syllabus.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-syllabus-msg">' +
            '<div class="bns-syllabus-msg__hero">' +
                '<span class="bns-syllabus-msg__hero-badge">' + escapeHtml(syllabus.eyebrow || 'Curriculum') + '</span>' +
                '<div class="bns-syllabus-msg__hero-icon" aria-hidden="true"><i class="fas fa-book-open"></i></div>' +
                '<h3>📚 ' + escapeHtml(syllabus.headline || 'Explore Our Complete Course Syllabus') + '</h3>' +
                (syllabus.question ? '<p class="bns-syllabus-msg__question">' + escapeHtml(syllabus.question) + '</p>' : '') +
                (syllabus.intro ? '<p class="bns-syllabus-msg__hero-sub">' + escapeHtml(syllabus.intro) + '</p>' : '') +
            '</div>' +

            (highlights
                ? '<div class="bns-syllabus-msg__card">' +
                    '<h4>🎯 ' + escapeHtml(syllabus.highlights_title || 'Course Highlights') + '</h4>' +
                    '<ul class="bns-syllabus-msg__list">' + highlights + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-syllabus-msg__view">' +
                '<div class="bns-syllabus-msg__view-icon" aria-hidden="true"><i class="fas fa-file-alt"></i></div>' +
                '<h4>📖 ' + escapeHtml(syllabus.view_title || 'View Complete Course Syllabus') + '</h4>' +
                (syllabus.view_url
                    ? '<a class="bns-syllabus-msg__cta" href="' + escapeAttr(syllabus.view_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-external-link-alt" aria-hidden="true"></i> ' + escapeHtml(syllabus.view_label || 'Open Full Syllabus') +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-syllabus-msg__final">' +
                (syllabus.punch ? '<p class="bns-syllabus-msg__punch">' + escapeHtml(syllabus.punch) + '</p>' : '') +
                (syllabus.brand ? '<em class="bns-syllabus-msg__brand">' + escapeHtml(syllabus.brand) + '</em>' : '') +
                (motto ? '<div class="bns-syllabus-msg__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderIntroSession(introsession) {
        if (!introsession) {
            return '';
        }

        var learn = (introsession.learn || []).map(function (text) {
            return '<li class="bns-introsession__item">' +
                '<span class="bns-introsession__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var motto = (introsession.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-introsession">' +
            '<div class="bns-introsession__hero">' +
                '<span class="bns-introsession__hero-badge">' + escapeHtml(introsession.eyebrow || 'Must Watch') + '</span>' +
                '<div class="bns-introsession__hero-icon" aria-hidden="true"><i class="fas fa-play-circle"></i></div>' +
                '<h3>🎥 ' + escapeHtml(introsession.headline || 'Watch Our FREE Introduction Session') + '</h3>' +
                (introsession.greeting ? '<p class="bns-introsession__greeting">' + escapeHtml(introsession.greeting) + '</p>' : '') +
                (introsession.intro ? '<p class="bns-introsession__hero-sub">' + escapeHtml(introsession.intro) + '</p>' : '') +
                (introsession.designed ? '<p class="bns-introsession__hero-sub">' + escapeHtml(introsession.designed) + '</p>' : '') +
                (introsession.clarity ? '<p class="bns-introsession__hero-sub">' + escapeHtml(introsession.clarity) + '</p>' : '') +
            '</div>' +

            (learn
                ? '<div class="bns-introsession__card">' +
                    '<h4>🎓 ' + escapeHtml(introsession.learn_title || 'What You Will Learn') + '</h4>' +
                    '<ul class="bns-introsession__list">' + learn + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-introsession__watch">' +
                '<div class="bns-introsession__watch-icon" aria-hidden="true"><i class="fas fa-video"></i></div>' +
                '<h4>' + escapeHtml(introsession.watch_title || 'FREE Introduction Session') + '</h4>' +
                (introsession.watch_url
                    ? '<a class="bns-introsession__cta" href="' + escapeAttr(introsession.watch_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-play" aria-hidden="true"></i> ' + escapeHtml(introsession.watch_label || 'Watch Here') +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-introsession__note">' +
                (introsession.request ? '<strong>' + escapeHtml(introsession.request) + '</strong>' : '') +
                (introsession.benefit ? '<p>' + escapeHtml(introsession.benefit) + '</p>' : '') +
            '</div>' +

            '<div class="bns-introsession__final">' +
                (introsession.family ? '<p>' + escapeHtml(introsession.family) + '</p>' : '') +
                (introsession.thanks ? '<strong class="bns-introsession__thanks">' + escapeHtml(introsession.thanks) + '</strong>' : '') +
                (motto ? '<div class="bns-introsession__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderPhotoGallery(photogallery) {
        if (!photogallery) {
            return '';
        }

        var reelPoints = (photogallery.reel_points || []).map(function (text) {
            return '<li class="bns-photogallery__item">' +
                '<span class="bns-photogallery__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var motto = (photogallery.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-photogallery">' +
            '<div class="bns-photogallery__hero">' +
                '<span class="bns-photogallery__hero-badge">' + escapeHtml(photogallery.eyebrow || 'After Seminar') + '</span>' +
                '<div class="bns-photogallery__hero-icon" aria-hidden="true"><i class="fas fa-images"></i></div>' +
                '<h3>📸 ' + escapeHtml(photogallery.headline || 'Previous Seminar Photo Gallery') + '</h3>' +
                (photogallery.greeting ? '<p class="bns-photogallery__greeting">' + escapeHtml(photogallery.greeting) + '</p>' : '') +
                (photogallery.intro ? '<p class="bns-photogallery__hero-sub">' + escapeHtml(photogallery.intro) + '</p>' : '') +
                (photogallery.glimpse ? '<p class="bns-photogallery__hero-sub">' + escapeHtml(photogallery.glimpse) + '</p>' : '') +
            '</div>' +

            '<div class="bns-photogallery__card bns-photogallery__card--gallery">' +
                '<div class="bns-photogallery__media-icon" aria-hidden="true"><i class="fas fa-camera-retro"></i></div>' +
                '<h4>' + escapeHtml(photogallery.gallery_title || 'Previous Seminar Photo Gallery') + '</h4>' +
                (photogallery.gallery_url
                    ? '<a class="bns-photogallery__cta bns-photogallery__cta--gallery" href="' + escapeAttr(photogallery.gallery_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-images" aria-hidden="true"></i> ' + escapeHtml(photogallery.gallery_label || 'Open Photo Gallery') +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-photogallery__card bns-photogallery__card--reel">' +
                '<h4>🎥 ' + escapeHtml(photogallery.reel_title || 'Please Also Watch Our Official Reel') + '</h4>' +
                (photogallery.reel_intro ? '<p>' + escapeHtml(photogallery.reel_intro) + '</p>' : '') +
                (photogallery.reel_request ? '<p class="bns-photogallery__request">' + escapeHtml(photogallery.reel_request) + '</p>' : '') +
                (reelPoints ? '<ul class="bns-photogallery__list">' + reelPoints + '</ul>' : '') +
                (photogallery.reel_url
                    ? '<a class="bns-photogallery__cta bns-photogallery__cta--reel" href="' + escapeAttr(photogallery.reel_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fab fa-instagram" aria-hidden="true"></i> ' + escapeHtml(photogallery.reel_label || 'Watch Official BNS Reel') +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-photogallery__final">' +
                (photogallery.inspire ? '<p>' + escapeHtml(photogallery.inspire) + '</p>' : '') +
                (photogallery.thanks ? '<strong class="bns-photogallery__thanks">' + escapeHtml(photogallery.thanks) + '</strong>' : '') +
                (photogallery.family ? '<p>' + escapeHtml(photogallery.family) + '</p>' : '') +
                (motto ? '<div class="bns-photogallery__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderSemThanks(semthanks) {
        if (!semthanks) {
            return '';
        }

        var insights = (semthanks.insights || []).map(function (text) {
            return '<li class="bns-semthanks__item">' +
                '<span class="bns-semthanks__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var nextSteps = (semthanks.next_steps || []).map(function (step) {
            return '<li class="bns-semthanks__next-item">' +
                '<span aria-hidden="true">' + escapeHtml(step.icon || '📌') + '</span>' +
                '<strong>' + escapeHtml(step.text || '') + '</strong>' +
                '</li>';
        }).join('');

        var gratitude = (semthanks.gratitude || []).map(function (text) {
            return '<li class="bns-semthanks__gratitude-item">' +
                '<span aria-hidden="true">🤝</span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var motto = (semthanks.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-semthanks">' +
            '<div class="bns-semthanks__hero">' +
                '<span class="bns-semthanks__hero-badge">' + escapeHtml(semthanks.eyebrow || 'Closing Note') + '</span>' +
                '<div class="bns-semthanks__hero-icon" aria-hidden="true"><i class="fas fa-praying-hands"></i></div>' +
                '<h3>🙏 ' + escapeHtml(semthanks.headline || 'Thank You') + '</h3>' +
                (semthanks.greeting ? '<p class="bns-semthanks__greeting">' + escapeHtml(semthanks.greeting) + '</p>' : '') +
                (semthanks.lead ? '<p class="bns-semthanks__lead">' + escapeHtml(semthanks.lead) + '</p>' : '') +
                (semthanks.appreciate ? '<p class="bns-semthanks__hero-sub">' + escapeHtml(semthanks.appreciate) + '</p>' : '') +
                (semthanks.presence ? '<p class="bns-semthanks__hero-sub">' + escapeHtml(semthanks.presence) + '</p>' : '') +
            '</div>' +

            (insights
                ? '<div class="bns-semthanks__card">' +
                    '<h4>🌟 ' + escapeHtml(semthanks.insights_title || 'We Hope You Gained Valuable Insights') + '</h4>' +
                    '<ul class="bns-semthanks__list">' + insights + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-semthanks__card bns-semthanks__card--next">' +
                '<h4>🎓 ' + escapeHtml(semthanks.next_title || 'Ready for the Next Step?') + '</h4>' +
                (semthanks.next_intro ? '<p>' + escapeHtml(semthanks.next_intro) + '</p>' : '') +
                (nextSteps ? '<ul class="bns-semthanks__next-list">' + nextSteps + '</ul>' : '') +
            '</div>' +

            '<div class="bns-semthanks__connect">' +
                '<h4>📲 ' + escapeHtml(semthanks.connect_title || 'Stay Connected') + '</h4>' +
                '<div class="bns-semthanks__connect-grid">' +
                    '<a class="bns-semthanks__connect-btn bns-semthanks__connect-btn--web" href="' + escapeAttr(semthanks.web_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-globe" aria-hidden="true"></i>' +
                        '<span><em>' + escapeHtml(semthanks.web_label || 'Official Website') + '</em><strong>Visit Website</strong></span>' +
                    '</a>' +
                    '<a class="bns-semthanks__connect-btn bns-semthanks__connect-btn--bot" href="' + escapeAttr(semthanks.bot_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                        '<span>' +
                            '<em>' + escapeHtml(semthanks.bot_label || 'BNS WhatsApp BOT') + '</em>' +
                            '<strong>' + escapeHtml(semthanks.bot_number || '') + '</strong>' +
                            (semthanks.bot_hint ? '<small>' + escapeHtml(semthanks.bot_hint) + '</small>' : '') +
                        '</span>' +
                    '</a>' +
                    '<a class="bns-semthanks__connect-btn bns-semthanks__connect-btn--channel" href="' + escapeAttr(semthanks.channel_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                        '<span><em>' + escapeHtml(semthanks.channel_label || 'WhatsApp Channel') + '</em><strong>Join Channel</strong></span>' +
                    '</a>' +
                '</div>' +
            '</div>' +

            '<div class="bns-semthanks__gratitude">' +
                '<h4>❤️ ' + escapeHtml(semthanks.gratitude_title || 'Our Gratitude') + '</h4>' +
                (semthanks.gratitude_intro ? '<p class="bns-semthanks__gratitude-intro">' + escapeHtml(semthanks.gratitude_intro) + '</p>' : '') +
                (gratitude ? '<ul class="bns-semthanks__gratitude-list">' + gratitude + '</ul>' : '') +
                (semthanks.mission ? '<p class="bns-semthanks__mission">' + escapeHtml(semthanks.mission) + '</p>' : '') +
            '</div>' +

            '<div class="bns-semthanks__final">' +
                (semthanks.again ? '<strong class="bns-semthanks__again">' + escapeHtml(semthanks.again) + '</strong>' : '') +
                (semthanks.family ? '<p>' + escapeHtml(semthanks.family) + '</p>' : '') +
                (motto ? '<div class="bns-semthanks__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderUsefulLinks(usefullinks) {
        if (!usefullinks) {
            return '';
        }

        var links = (usefullinks.links || []).map(function (link) {
            return '<a class="bns-usefullinks__card bns-usefullinks__card--' + escapeAttr(link.tone || 'web') + '" href="' + escapeAttr(link.url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                '<span class="bns-usefullinks__icon" aria-hidden="true"><i class="' + escapeAttr(link.fa || 'fas fa-link') + '"></i></span>' +
                '<span class="bns-usefullinks__body">' +
                    '<strong>' + escapeHtml((link.icon ? link.icon + ' ' : '') + (link.title || '')) + '</strong>' +
                    (link.desc ? '<em>' + escapeHtml(link.desc) + '</em>' : '') +
                    (link.meta ? '<small>' + escapeHtml(link.meta) + '</small>' : '') +
                    '<span class="bns-usefullinks__cta">' + escapeHtml(link.label || 'Open') + ' <i class="fas fa-arrow-right" aria-hidden="true"></i></span>' +
                '</span>' +
            '</a>';
        }).join('');

        var social = (usefullinks.social || []).map(function (item) {
            return '<a class="bns-usefullinks__social bns-usefullinks__social--' + escapeAttr(item.tone || 'web') + '" href="' + escapeAttr(item.url || '#') + '" target="_blank" rel="noopener noreferrer" title="' + escapeAttr(item.label || '') + '">' +
                '<i class="' + escapeAttr(item.fa || 'fas fa-link') + '" aria-hidden="true"></i>' +
                '<span>' + escapeHtml(item.label || '') + '</span>' +
            '</a>';
        }).join('');

        var venueLines = (usefullinks.venue_lines || []).map(function (line, index) {
            return index === 0
                ? '<strong>' + escapeHtml(line) + '</strong>'
                : '<span>' + escapeHtml(line) + '</span>';
        }).join('');

        var motto = (usefullinks.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-usefullinks">' +
            '<div class="bns-usefullinks__hero">' +
                '<span class="bns-usefullinks__hero-badge">' + escapeHtml(usefullinks.eyebrow || 'Save for Later') + '</span>' +
                '<div class="bns-usefullinks__hero-icon" aria-hidden="true"><i class="fas fa-link"></i></div>' +
                '<h3>🔗 ' + escapeHtml(usefullinks.headline || 'Important Useful Links') + '</h3>' +
                (usefullinks.thanks ? '<p class="bns-usefullinks__greeting">' + escapeHtml(usefullinks.thanks) + '</p>' : '') +
                (usefullinks.intro ? '<p class="bns-usefullinks__hero-sub">' + escapeHtml(usefullinks.intro) + '</p>' : '') +
            '</div>' +

            (links ? '<div class="bns-usefullinks__grid">' + links + '</div>' : '') +

            (social
                ? '<div class="bns-usefullinks__social-wrap">' +
                    '<h4>🌐 ' + escapeHtml(usefullinks.social_title || 'Follow Us on Social Media') + '</h4>' +
                    '<div class="bns-usefullinks__social-grid">' + social + '</div>' +
                  '</div>'
                : '') +

            '<div class="bns-usefullinks__venue">' +
                '<h4>📍 ' + escapeHtml(usefullinks.venue_title || 'Seminar Venue') + '</h4>' +
                '<div class="bns-usefullinks__venue-lines">' + venueLines + '</div>' +
                (usefullinks.map_url
                    ? '<a class="bns-usefullinks__map" href="' + escapeAttr(usefullinks.map_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> ' + escapeHtml(usefullinks.map_label || 'Open Google Map') +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-usefullinks__final">' +
                (usefullinks.closing ? '<p>' + escapeHtml(usefullinks.closing) + '</p>' : '') +
                (motto ? '<div class="bns-usefullinks__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderScholarship(scholarship) {
        if (!scholarship) {
            return '';
        }

        var nonMember = scholarship.non_member || {};
        var member = scholarship.member || {};

        var process = (scholarship.process || []).map(function (text, index) {
            return '<li class="bns-scholarship__step">' +
                '<span class="bns-scholarship__num" aria-hidden="true">' + String(index + 1).padStart(2, '0') + '</span>' +
                '<span>' + escapeHtml(text) + '</span>' +
                '</li>';
        }).join('');

        var notes = (scholarship.notes || []).map(function (text) {
            return '<li class="bns-scholarship__note">' +
                '<span class="bns-scholarship__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<span>' + escapeHtml(text) + '</span>' +
                '</li>';
        }).join('');

        var motto = (scholarship.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-scholarship">' +
            '<div class="bns-scholarship__hero">' +
                '<span class="bns-scholarship__hero-badge">' + escapeHtml(scholarship.eyebrow || 'Venue Partner Benefit') + '</span>' +
                '<div class="bns-scholarship__hero-icon" aria-hidden="true"><i class="fas fa-award"></i></div>' +
                '<h3>🎓 ' + escapeHtml(scholarship.headline || 'Scholarship Information') + '</h3>' +
                (scholarship.intro ? '<p class="bns-scholarship__hero-sub">' + escapeHtml(scholarship.intro) + '</p>' : '') +
            '</div>' +

            '<div class="bns-scholarship__fees">' +
                '<h4 class="bns-scholarship__fees-title">📢 ' + escapeHtml(scholarship.fee_title || 'Admission Fee') + '</h4>' +
                '<div class="bns-scholarship__fee-grid">' +
                    '<div class="bns-scholarship__fee-card">' +
                        '<span class="bns-scholarship__fee-label">' + escapeHtml((nonMember.icon || '👤') + ' ' + (nonMember.label || 'For Non-Members')) + '</span>' +
                        '<em>' + escapeHtml(nonMember.course_fee || '') + '</em>' +
                        '<span class="bns-scholarship__fee-total-label">' + escapeHtml(nonMember.total_label || 'Total Payable') + '</span>' +
                        '<strong class="bns-scholarship__fee-total">' + escapeHtml(nonMember.total || '') + '</strong>' +
                    '</div>' +
                    '<div class="bns-scholarship__fee-card bns-scholarship__fee-card--member">' +
                        '<span class="bns-scholarship__fee-label">' + escapeHtml((member.icon || '🌟') + ' ' + (member.label || 'For Permanent Members')) + '</span>' +
                        '<em>' + escapeHtml(member.course_fee || '') + '</em>' +
                        '<span class="bns-scholarship__fee-total-label">' + escapeHtml(member.total_label || 'Effective Fee') + '</span>' +
                        '<strong class="bns-scholarship__fee-total">' + escapeHtml(member.total || '') + '</strong>' +
                        ((member.benefit)
                            ? '<span class="bns-scholarship__benefit">' +
                                escapeHtml(member.benefit_label || 'Special Benefit') + ': <b>' + escapeHtml(member.benefit) + '</b>' +
                              '</span>'
                            : '') +
                    '</div>' +
                '</div>' +
            '</div>' +

            (process
                ? '<div class="bns-scholarship__card">' +
                    '<h4>📋 ' + escapeHtml(scholarship.process_title || 'Admission Process') + '</h4>' +
                    '<ol class="bns-scholarship__steps">' + process + '</ol>' +
                  '</div>'
                : '') +

            (notes
                ? '<div class="bns-scholarship__card bns-scholarship__card--notes">' +
                    '<h4>📌 ' + escapeHtml(scholarship.notes_title || 'Important Instructions') + '</h4>' +
                    '<ul class="bns-scholarship__notes">' + notes + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-scholarship__assist">' +
                '<h4>🙋 ' + escapeHtml(scholarship.assist_title || 'Need Any Assistance?') + '</h4>' +
                (scholarship.assist ? '<p>' + escapeHtml(scholarship.assist) + '</p>' : '') +
                (scholarship.pay_url
                    ? '<a class="bns-scholarship__pay" href="' + escapeAttr(scholarship.pay_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-credit-card" aria-hidden="true"></i> ' + escapeHtml(scholarship.pay_label || 'Pay Now') +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-scholarship__final">' +
                (scholarship.thanks ? '<strong class="bns-scholarship__thanks">' + escapeHtml(scholarship.thanks) + '</strong>' : '') +
                (scholarship.family ? '<p>' + escapeHtml(scholarship.family) + '</p>' : '') +
                (motto ? '<div class="bns-scholarship__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderAdmitCounter(admitcounter) {
        if (!admitcounter) {
            return '';
        }

        function checkList(items, strong) {
            return (items || []).map(function (text) {
                return '<li class="bns-admitcounter__item">' +
                    '<span class="bns-admitcounter__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                    (strong ? '<strong>' + escapeHtml(text) + '</strong>' : '<span>' + escapeHtml(text) + '</span>') +
                    '</li>';
            }).join('');
        }

        var whoItems = checkList(admitcounter.who_items, true);
        var helpItems = checkList(admitcounter.help_items, true);
        var payItems = checkList(admitcounter.pay_items, true);

        var assistLines = (admitcounter.assist_lines || []).map(function (text) {
            return '<p>' + escapeHtml(text) + '</p>';
        }).join('');

        var motto = (admitcounter.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-admitcounter">' +
            '<div class="bns-admitcounter__hero">' +
                '<span class="bns-admitcounter__hero-badge">' + escapeHtml(admitcounter.eyebrow || 'During Seminar') + '</span>' +
                '<div class="bns-admitcounter__hero-icon" aria-hidden="true"><i class="fas fa-university"></i></div>' +
                '<h3>🎓 ' + escapeHtml(admitcounter.headline || 'Admission Counter Open') + '</h3>' +
                (admitcounter.thanks ? '<p class="bns-admitcounter__greeting">' + escapeHtml(admitcounter.thanks) + '</p>' : '') +
                (admitcounter.inspire ? '<p class="bns-admitcounter__hero-sub">' + escapeHtml(admitcounter.inspire) + '</p>' : '') +
                (admitcounter.open ? '<strong class="bns-admitcounter__open">' + escapeHtml(admitcounter.open) + '</strong>' : '') +
            '</div>' +

            '<div class="bns-admitcounter__card">' +
                '<h4>🎯 ' + escapeHtml(admitcounter.who_title || 'Who Should Visit?') + '</h4>' +
                (admitcounter.who_intro ? '<p class="bns-admitcounter__intro">' + escapeHtml(admitcounter.who_intro) + '</p>' : '') +
                (whoItems ? '<ul class="bns-admitcounter__list">' + whoItems + '</ul>' : '') +
                (admitcounter.who_closing ? '<p class="bns-admitcounter__closing-line">' + escapeHtml(admitcounter.who_closing) + '</p>' : '') +
            '</div>' +

            '<div class="bns-admitcounter__card">' +
                '<h4>📚 ' + escapeHtml(admitcounter.help_title || 'Admission Team Will Help You With') + '</h4>' +
                (helpItems ? '<ul class="bns-admitcounter__list">' + helpItems + '</ul>' : '') +
            '</div>' +

            '<div class="bns-admitcounter__card bns-admitcounter__card--pay">' +
                '<h4>💳 ' + escapeHtml(admitcounter.pay_title || 'Payment Options') + '</h4>' +
                (payItems ? '<ul class="bns-admitcounter__list bns-admitcounter__list--pay">' + payItems + '</ul>' : '') +
            '</div>' +

            '<div class="bns-admitcounter__assist">' +
                '<h4>🙋 ' + escapeHtml(admitcounter.assist_title || 'Need Any Assistance?') + '</h4>' +
                assistLines +
            '</div>' +

            '<div class="bns-admitcounter__urgent">' +
                '<h4>⏳ ' + escapeHtml(admitcounter.urgent_title || 'Important Information') + '</h4>' +
                (admitcounter.urgent_basis ? '<strong class="bns-admitcounter__basis">' + escapeHtml(admitcounter.urgent_basis) + '</strong>' : '') +
                (admitcounter.urgent_note ? '<p>' + escapeHtml(admitcounter.urgent_note) + '</p>' : '') +
                (admitcounter.urgent_cta ? '<p class="bns-admitcounter__urgent-cta">' + escapeHtml(admitcounter.urgent_cta) + '</p>' : '') +
            '</div>' +

            '<div class="bns-admitcounter__connect">' +
                '<h4>📲 ' + escapeHtml(admitcounter.connect_title || 'Stay Connected') + '</h4>' +
                '<div class="bns-admitcounter__connect-grid">' +
                    '<a class="bns-admitcounter__connect-btn bns-admitcounter__connect-btn--bot" href="' + escapeAttr(admitcounter.bot_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                        '<span>' +
                            '<em>' + escapeHtml(admitcounter.bot_label || 'BNS WhatsApp BOT') + '</em>' +
                            '<strong>' + escapeHtml(admitcounter.bot_number || '') + '</strong>' +
                            (admitcounter.bot_hint ? '<small>' + escapeHtml(admitcounter.bot_hint) + '</small>' : '') +
                        '</span>' +
                    '</a>' +
                    '<a class="bns-admitcounter__connect-btn bns-admitcounter__connect-btn--channel" href="' + escapeAttr(admitcounter.channel_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                        '<span>' +
                            '<em>' + escapeHtml(admitcounter.channel_label || 'WhatsApp Channel') + '</em>' +
                            '<strong>Join Channel</strong>' +
                        '</span>' +
                    '</a>' +
                    '<a class="bns-admitcounter__connect-btn bns-admitcounter__connect-btn--web" href="' + escapeAttr(admitcounter.web_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-globe" aria-hidden="true"></i>' +
                        '<span>' +
                            '<em>' + escapeHtml(admitcounter.web_label || 'Official Website') + '</em>' +
                            '<strong>Visit Website</strong>' +
                        '</span>' +
                    '</a>' +
                '</div>' +
            '</div>' +

            '<div class="bns-admitcounter__final">' +
                (admitcounter.closing_thanks ? '<strong class="bns-admitcounter__thanks">' + escapeHtml(admitcounter.closing_thanks) + '</strong>' : '') +
                (admitcounter.family ? '<p>' + escapeHtml(admitcounter.family) + '</p>' : '') +
                (motto ? '<div class="bns-admitcounter__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderInstructions(instructions) {
        if (!instructions) {
            return '';
        }

        var sections = (instructions.sections || []).map(function (section) {
            var items = (section.items || []).map(function (text) {
                return '<li class="bns-instructions__item">' +
                    '<span class="bns-instructions__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                    '<span>' + escapeHtml(text) + '</span>' +
                    '</li>';
            }).join('');

            return '<div class="bns-instructions__section">' +
                '<h4><span aria-hidden="true">' + escapeHtml(section.icon || '📌') + '</span> ' + escapeHtml(section.title || '') + '</h4>' +
                (items ? '<ul class="bns-instructions__list">' + items + '</ul>' : '') +
                '</div>';
        }).join('');

        var helpLines = (instructions.help_lines || []).map(function (text) {
            return '<p>' + escapeHtml(text) + '</p>';
        }).join('');

        var admitItems = (instructions.admit_items || []).map(function (text) {
            return '<li class="bns-instructions__item">' +
                '<span class="bns-instructions__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var partners = (instructions.partners || []).map(function (partner) {
            return '<div class="bns-instructions__partner">' +
                '<span>' + escapeHtml(partner.label || 'Partner') + '</span>' +
                '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                '</div>';
        }).join('');

        var motto = (instructions.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-instructions">' +
            '<div class="bns-instructions__hero">' +
                '<span class="bns-instructions__hero-badge">' + escapeHtml(instructions.eyebrow || 'Seminar Day Guide') + '</span>' +
                '<div class="bns-instructions__hero-icon" aria-hidden="true"><i class="fas fa-clipboard-check"></i></div>' +
                '<h3>📢 ' + escapeHtml(instructions.headline || 'Seminar Instructions') + '</h3>' +
                (instructions.greeting ? '<p class="bns-instructions__greeting">' + escapeHtml(instructions.greeting) + '</p>' : '') +
                (instructions.intro ? '<p class="bns-instructions__hero-sub">' + escapeHtml(instructions.intro) + '</p>' : '') +
            '</div>' +

            (sections ? '<div class="bns-instructions__grid">' + sections + '</div>' : '') +

            '<div class="bns-instructions__help">' +
                '<h4>🙋 ' + escapeHtml(instructions.help_title || 'Need Any Help?') + '</h4>' +
                helpLines +
            '</div>' +

            '<div class="bns-instructions__admit">' +
                '<h4>🎓 ' + escapeHtml(instructions.admit_title || 'Admission Information') + '</h4>' +
                (instructions.admit_intro ? '<p class="bns-instructions__admit-intro">' + escapeHtml(instructions.admit_intro) + '</p>' : '') +
                (instructions.admit_assist ? '<p class="bns-instructions__admit-assist">' + escapeHtml(instructions.admit_assist) + '</p>' : '') +
                (admitItems ? '<ul class="bns-instructions__list">' + admitItems + '</ul>' : '') +
            '</div>' +

            '<div class="bns-instructions__connect">' +
                '<h4>📲 ' + escapeHtml(instructions.connect_title || 'Stay Connected') + '</h4>' +
                '<div class="bns-instructions__connect-grid">' +
                    '<a class="bns-instructions__connect-btn bns-instructions__connect-btn--bot" href="' + escapeAttr(instructions.bot_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                        '<span>' +
                            '<em>' + escapeHtml(instructions.bot_label || 'BNS WhatsApp BOT') + '</em>' +
                            '<strong>' + escapeHtml(instructions.bot_number || '') + '</strong>' +
                            (instructions.bot_hint ? '<small>' + escapeHtml(instructions.bot_hint) + '</small>' : '') +
                        '</span>' +
                    '</a>' +
                    '<a class="bns-instructions__connect-btn bns-instructions__connect-btn--channel" href="' + escapeAttr(instructions.channel_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                        '<span>' +
                            '<em>' + escapeHtml(instructions.channel_label || 'WhatsApp Channel') + '</em>' +
                            '<strong>Join Channel</strong>' +
                        '</span>' +
                    '</a>' +
                    '<a class="bns-instructions__connect-btn bns-instructions__connect-btn--web" href="' + escapeAttr(instructions.web_url || '#') + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-globe" aria-hidden="true"></i>' +
                        '<span>' +
                            '<em>' + escapeHtml(instructions.web_label || 'Official Website') + '</em>' +
                            '<strong>Visit Website</strong>' +
                        '</span>' +
                    '</a>' +
                '</div>' +
            '</div>' +

            (partners ? '<div class="bns-instructions__partners">' + partners + '</div>' : '') +

            '<div class="bns-instructions__final">' +
                (instructions.thanks ? '<strong class="bns-instructions__thanks">' + escapeHtml(instructions.thanks) + '</strong>' : '') +
                (instructions.wish ? '<p>' + escapeHtml(instructions.wish) + '</p>' : '') +
                (motto ? '<div class="bns-instructions__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderAttendance(attendance) {
        if (!attendance) {
            return '';
        }

        function checkItems(items) {
            return (items || []).map(function (text) {
                return '<li class="bns-attendance-msg__item">' +
                    '<span class="bns-attendance-msg__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                    '<strong>' + escapeHtml(text) + '</strong>' +
                    '</li>';
            }).join('');
        }

        var channelBenefits = checkItems(attendance.channel_benefits);
        var botFeatures = checkItems(attendance.bot_features);

        var motto = (attendance.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-attendance-msg">' +
            '<div class="bns-attendance-msg__hero">' +
                '<span class="bns-attendance-msg__hero-badge">' + escapeHtml(attendance.eyebrow || 'Seminar Welcome') + '</span>' +
                '<div class="bns-attendance-msg__hero-icon" aria-hidden="true"><i class="fas fa-hand-holding-heart"></i></div>' +
                '<h3>🌟 ' + escapeHtml(attendance.headline || 'Welcome to Business Navachar School (BNS)') + '</h3>' +
                (attendance.greeting ? '<p class="bns-attendance-msg__greeting">' + escapeHtml(attendance.greeting) + '</p>' : '') +
                (attendance.intro ? '<p class="bns-attendance-msg__hero-sub">' + escapeHtml(attendance.intro) + '</p>' : '') +
                (attendance.thanks ? '<p class="bns-attendance-msg__hero-sub">' + escapeHtml(attendance.thanks) + '</p>' : '') +
            '</div>' +

            '<div class="bns-attendance-msg__card bns-attendance-msg__card--primary">' +
                '<h4><span>1️⃣ ' + escapeHtml(attendance.attendance_title || 'Attendance Registration') + '</span></h4>' +
                (attendance.attendance_intro ? '<p>' + escapeHtml(attendance.attendance_intro) + '</p>' : '') +
                (attendance.attendance_note ? '<p class="bns-attendance-msg__note">' + escapeHtml(attendance.attendance_note) + '</p>' : '') +
                (attendance.attendance_url
                    ? '<a class="bns-attendance-msg__cta bns-attendance-msg__cta--attendance" href="' + escapeAttr(attendance.attendance_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-user-check" aria-hidden="true"></i> ' + escapeHtml(attendance.attendance_label || 'Mark Attendance') +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-attendance-msg__card">' +
                '<h4><span>2️⃣ ' + escapeHtml(attendance.channel_title || 'Join Our Official WhatsApp Channel') + '</span></h4>' +
                (attendance.channel_intro ? '<p>' + escapeHtml(attendance.channel_intro) + '</p>' : '') +
                (attendance.channel_list_title ? '<p class="bns-attendance-msg__list-title">' + escapeHtml(attendance.channel_list_title) + '</p>' : '') +
                (channelBenefits ? '<ul class="bns-attendance-msg__list">' + channelBenefits + '</ul>' : '') +
                (attendance.channel_url
                    ? '<a class="bns-attendance-msg__cta bns-attendance-msg__cta--channel" href="' + escapeAttr(attendance.channel_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fab fa-whatsapp" aria-hidden="true"></i> ' + escapeHtml(attendance.channel_label || 'Join WhatsApp Channel') +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-attendance-msg__card">' +
                '<h4><span>3️⃣ ' + escapeHtml(attendance.bot_title || 'Need More Information?') + '</span></h4>' +
                '<div class="bns-attendance-msg__bot">' +
                    '<span>' + escapeHtml(attendance.bot_label || 'BNS WhatsApp BOT') + '</span>' +
                    '<strong>' + escapeHtml(attendance.bot_number || '') + '</strong>' +
                    (attendance.bot_intro ? '<em>' + escapeHtml(attendance.bot_intro) + '</em>' : '') +
                    (botFeatures ? '<ul class="bns-attendance-msg__list">' + botFeatures + '</ul>' : '') +
                    (attendance.bot_url
                        ? '<a class="bns-attendance-msg__cta bns-attendance-msg__cta--bot" href="' + escapeAttr(attendance.bot_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i> Message BOT' +
                          '</a>'
                        : '') +
                '</div>' +
            '</div>' +

            '<div class="bns-attendance-msg__card bns-attendance-msg__card--admit">' +
                '<h4><span>4️⃣ ' + escapeHtml(attendance.admit_title || 'Book Your Admission') + '</span></h4>' +
                (attendance.admit_open ? '<p class="bns-attendance-msg__admit-open">' + escapeHtml(attendance.admit_open) + '</p>' : '') +
                (attendance.admit_seats ? '<strong class="bns-attendance-msg__seats">' + escapeHtml(attendance.admit_seats) + '</strong>' : '') +
                (attendance.admit_cta ? '<p>' + escapeHtml(attendance.admit_cta) + '</p>' : '') +
                (attendance.admit_url
                    ? '<a class="bns-attendance-msg__cta bns-attendance-msg__cta--admit" href="' + escapeAttr(attendance.admit_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-graduation-cap" aria-hidden="true"></i> ' + escapeHtml(attendance.admit_label || 'Book Admission') +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-attendance-msg__final">' +
                (attendance.inspire ? '<p>' + escapeHtml(attendance.inspire) + '</p>' : '') +
                (attendance.closing ? '<strong class="bns-attendance-msg__closing">' + escapeHtml(attendance.closing) + '</strong>' : '') +
                (attendance.brand ? '<em class="bns-attendance-msg__brand">' + escapeHtml(attendance.brand) + '</em>' : '') +
                (motto ? '<div class="bns-attendance-msg__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderWelcomeReg(welcomereg) {
        if (!welcomereg) {
            return '';
        }

        var steps = (welcomereg.steps || []).map(function (text, index) {
            return '<li class="bns-welcomereg__step">' +
                '<span class="bns-welcomereg__num" aria-hidden="true">' + String(index + 1).padStart(2, '0') + '</span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var attendance = (welcomereg.attendance || []).map(function (text) {
            return '<li class="bns-welcomereg__item">' +
                '<span class="bns-welcomereg__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var after = (welcomereg.after || []).map(function (text) {
            return '<li class="bns-welcomereg__item">' +
                '<span class="bns-welcomereg__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var instructions = (welcomereg.instructions || []).map(function (item) {
            return '<li class="bns-welcomereg__note">' +
                '<span aria-hidden="true">' + escapeHtml(item.icon || '📌') + '</span>' +
                '<span>' + escapeHtml(item.text || '') + '</span>' +
                '</li>';
        }).join('');

        var partners = (welcomereg.partners || []).map(function (partner) {
            return '<div class="bns-welcomereg__partner">' +
                '<span>' + escapeHtml(partner.label || 'Partner') + '</span>' +
                '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                '</div>';
        }).join('');

        var motto = (welcomereg.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-welcomereg">' +
            '<div class="bns-welcomereg__hero">' +
                '<span class="bns-welcomereg__hero-badge">' + escapeHtml(welcomereg.eyebrow || 'Check-In Process') + '</span>' +
                '<div class="bns-welcomereg__hero-icon" aria-hidden="true"><i class="fas fa-clipboard-list"></i></div>' +
                '<h3>📲 ' + escapeHtml(welcomereg.headline || 'Welcome Registration Process') + '</h3>' +
                (welcomereg.intro ? '<p class="bns-welcomereg__intro">' + escapeHtml(welcomereg.intro) + '</p>' : '') +
                (welcomereg.request ? '<p class="bns-welcomereg__hero-sub">' + escapeHtml(welcomereg.request) + '</p>' : '') +
                (welcomereg.duration ? '<strong class="bns-welcomereg__duration">' + escapeHtml(welcomereg.duration) + '</strong>' : '') +
            '</div>' +

            (steps
                ? '<div class="bns-welcomereg__card">' +
                    '<h4><span>📝 ' + escapeHtml(welcomereg.steps_title || 'How to Complete Your Welcome Registration') + '</span></h4>' +
                    '<ol class="bns-welcomereg__steps">' + steps + '</ol>' +
                  '</div>'
                : '') +

            ((attendance || welcomereg.attendance_intro)
                ? '<div class="bns-welcomereg__card bns-welcomereg__card--attendance">' +
                    '<h4><span>✅ ' + escapeHtml(welcomereg.attendance_title || 'Online Attendance') + '</span></h4>' +
                    (welcomereg.attendance_intro ? '<p class="bns-welcomereg__card-intro">' + escapeHtml(welcomereg.attendance_intro) + '</p>' : '') +
                    (attendance ? '<ul class="bns-welcomereg__list">' + attendance + '</ul>' : '') +
                  '</div>'
                : '') +

            '<div class="bns-welcomereg__help-card">' +
                '<h4>🙋 ' + escapeHtml(welcomereg.help_title || 'Need Any Help?') + '</h4>' +
                (welcomereg.help_intro ? '<p>' + escapeHtml(welcomereg.help_intro) + '</p>' : '') +
                (welcomereg.help ? '<strong>' + escapeHtml(welcomereg.help) + '</strong>' : '') +
                (welcomereg.help_note ? '<p>' + escapeHtml(welcomereg.help_note) + '</p>' : '') +
            '</div>' +

            (after
                ? '<div class="bns-welcomereg__card">' +
                    '<h4><span>📍 ' + escapeHtml(welcomereg.after_title || 'After Registration') + '</span></h4>' +
                    '<ul class="bns-welcomereg__list">' + after + '</ul>' +
                  '</div>'
                : '') +

            (instructions
                ? '<div class="bns-welcomereg__card">' +
                    '<h4><span>📢 ' + escapeHtml(welcomereg.instructions_title || 'Important Instructions') + '</span></h4>' +
                    '<ul class="bns-welcomereg__notes">' + instructions + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-welcomereg__assist">' +
                '<h4>📲 ' + escapeHtml(welcomereg.assist_title || 'Need Additional Assistance?') + '</h4>' +
                '<div class="bns-welcomereg__assist-grid">' +
                    (welcomereg.bot_url
                        ? '<a class="bns-welcomereg__assist-btn bns-welcomereg__assist-btn--bot" href="' + escapeAttr(welcomereg.bot_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span><em>' + escapeHtml(welcomereg.bot_label || 'WhatsApp BOT') + '</em>' + escapeHtml(welcomereg.bot_hint || '') + '</span>' +
                          '</a>'
                        : '') +
                    (welcomereg.website
                        ? '<a class="bns-welcomereg__assist-btn bns-welcomereg__assist-btn--web" href="' + escapeAttr(welcomereg.website) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-globe" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(welcomereg.website.replace(/^https?:\/\//, '')) + '</span>' +
                          '</a>'
                        : '') +
                    (welcomereg.channel_url
                        ? '<a class="bns-welcomereg__assist-btn bns-welcomereg__assist-btn--channel" href="' + escapeAttr(welcomereg.channel_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(welcomereg.channel_title || 'WhatsApp Channel') + '</span>' +
                          '</a>'
                        : '') +
                '</div>' +
            '</div>' +

            (partners ? '<div class="bns-welcomereg__partners">' + partners + '</div>' : '') +

            '<div class="bns-welcomereg__final">' +
                (welcomereg.thanks ? '<strong class="bns-welcomereg__thanks">' + escapeHtml(welcomereg.thanks) + '</strong>' : '') +
                (welcomereg.closing ? '<p>' + escapeHtml(welcomereg.closing) + '</p>' : '') +
                (motto ? '<div class="bns-welcomereg__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderVenueGps(venuegps) {
        if (!venuegps) {
            return '';
        }

        var venue = venuegps.venue || {};
        var venueLines = (venue.lines || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        var travel = (venuegps.travel || []).map(function (text) {
            return '<li class="bns-venuegps__item">' +
                '<span class="bns-venuegps__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var tips = (venuegps.final_tips || []).map(function (text) {
            return '<li class="bns-venuegps__item">' +
                '<span class="bns-venuegps__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var partners = venuegps.partners || [];
        var partnersHtml = partners.length
            ? '<div class="bns-venuegps__partners">' +
                partners.map(function (partner) {
                    return '<div class="bns-venuegps__partner">' +
                        '<span>' + escapeHtml(partner.label || 'Partner') + '</span>' +
                        '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                    '</div>';
                }).join('') +
              '</div>'
            : '';

        var motto = (venuegps.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        var sessions = venuegps.sessions || [];
        var eventHtml = '';
        if (sessions.length) {
            eventHtml = '<div class="bns-venuegps__sessions">' +
                sessions.map(function (session) {
                    var tone = session.tone === 'blue' ? 'blue' : 'green';
                    return '<div class="bns-venuegps__session bns-venuegps__session--' + tone + '">' +
                        '<strong class="bns-venuegps__session-label">' + escapeHtml(session.label || 'Session') + '</strong>' +
                        '<div class="bns-venuegps__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(session.date || '') + '</strong></div></div>' +
                        '<div class="bns-venuegps__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(session.time || '') + '</strong></div></div>' +
                        (session.reporting
                            ? '<div class="bns-venuegps__event-row bns-venuegps__event-row--accent bns-venuegps__event-row--full"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(session.reporting) + '</strong></div></div>'
                            : '') +
                    '</div>';
                }).join('') +
            '</div>';
        } else {
            eventHtml = '<div class="bns-venuegps__event">' +
                '<div class="bns-venuegps__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(venuegps.date || '') + '</strong></div></div>' +
                '<div class="bns-venuegps__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(venuegps.time || '') + '</strong></div></div>' +
                '<div class="bns-venuegps__event-row bns-venuegps__event-row--accent"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(venuegps.report_time || '') + '</strong></div></div>' +
            '</div>';
        }

        var reportTimes = venuegps.report_times || [];
        var reportTimesHtml = reportTimes.length
            ? '<div class="bns-venuegps__report-times">' +
                '<strong class="bns-venuegps__report-label">' + escapeHtml(venuegps.report_label || 'Reporting Time') + '</strong>' +
                reportTimes.map(function (item) {
                    var tone = item.tone === 'blue' ? 'blue' : 'green';
                    return '<div class="bns-venuegps__report-chip bns-venuegps__report-chip--' + tone + '">' +
                        '<span>' + escapeHtml(item.label || 'Session') + '</span>' +
                        '<strong>' + escapeHtml(item.time || '') + '</strong>' +
                    '</div>';
                }).join('') +
              '</div>'
            : (venuegps.report_time
                ? '<strong class="bns-venuegps__report-time">Reporting Time: ' + escapeHtml(venuegps.report_time) + '</strong>'
                : '');

        var connectHtml = (venuegps.channel_url || venuegps.website)
            ? '<div class="bns-venuegps__connect">' +
                '<h4>🌐 ' + escapeHtml(venuegps.connect_title || 'Stay Connected') + '</h4>' +
                '<div class="bns-venuegps__help-grid">' +
                    (venuegps.channel_url
                        ? '<a class="bns-venuegps__help-btn bns-venuegps__help-btn--channel" href="' + escapeAttr(venuegps.channel_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(venuegps.channel_title || 'WhatsApp Channel') + '</span>' +
                          '</a>'
                        : '') +
                    (venuegps.website
                        ? '<a class="bns-venuegps__help-btn bns-venuegps__help-btn--web" href="' + escapeAttr(venuegps.website) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-globe" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(venuegps.website.replace(/^https?:\/\//, '')) + '</span>' +
                          '</a>'
                        : '') +
                '</div>' +
              '</div>'
            : '';

        return '<div class="bns-venuegps">' +
            '<div class="bns-venuegps__hero">' +
                '<span class="bns-venuegps__hero-badge">' + escapeHtml(venuegps.eyebrow || 'Navigation Ready') + '</span>' +
                '<div class="bns-venuegps__hero-icon" aria-hidden="true"><i class="fas fa-map-marked-alt"></i></div>' +
                '<h3>📍 ' + escapeHtml(venuegps.headline || 'Venue & GPS Reminder') + '</h3>' +
                (venuegps.greeting ? '<p class="bns-venuegps__greeting">' + escapeHtml(venuegps.greeting) + '</p>' : '') +
                (venuegps.intro ? '<p class="bns-venuegps__hero-sub">' + escapeHtml(venuegps.intro) + '</p>' : '') +
                (venuegps.intro_note ? '<p class="bns-venuegps__hero-sub">' + escapeHtml(venuegps.intro_note) + '</p>' : '') +
            '</div>' +

            eventHtml +

            (venue.title || venueLines
                ? '<div class="bns-venuegps__card">' +
                    '<h4><span>📍 Venue</span></h4>' +
                    (venue.title ? '<strong class="bns-venuegps__venue-title">' + escapeHtml(venue.title) + '</strong>' : '') +
                    (venueLines ? '<ul class="bns-venuegps__venue-lines">' + venueLines + '</ul>' : '') +
                  '</div>'
                : '') +

            (venue.maps_url
                ? '<div class="bns-venuegps__maps-card">' +
                    '<h4>🗺️ ' + escapeHtml(venue.maps_title || 'Google Maps Location') + '</h4>' +
                    (venue.maps_hint ? '<p>' + escapeHtml(venue.maps_hint) + '</p>' : '') +
                    '<a class="bns-venuegps__maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-location-arrow" aria-hidden="true"></i> Start Google Maps Navigation' +
                    '</a>' +
                  '</div>'
                : '') +

            partnersHtml +

            (travel
                ? '<div class="bns-venuegps__card">' +
                    '<h4><span>🚗 ' + escapeHtml(venuegps.travel_title || 'Travel Reminder') + '</span></h4>' +
                    '<ul class="bns-venuegps__list">' + travel + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-venuegps__report">' +
                '<h4>⏰ ' + escapeHtml(venuegps.report_title || 'Reporting Reminder') + '</h4>' +
                (venuegps.report_intro ? '<p class="bns-venuegps__report-intro">' + escapeHtml(venuegps.report_intro) + '</p>' : '') +
                reportTimesHtml +
                (venuegps.report_note ? '<p>' + escapeHtml(venuegps.report_note) + '</p>' : '') +
                (venuegps.seats ? '<div class="bns-venuegps__seats"><span aria-hidden="true">💺</span><strong>' + escapeHtml(venuegps.seats) + '</strong></div>' : '') +
            '</div>' +

            '<div class="bns-venuegps__options">' +
                '<h4>🚆 ' + escapeHtml(venuegps.options_title || 'Travel Options') + '</h4>' +
                (venuegps.station ? '<p><strong>' + escapeHtml(venuegps.station) + '</strong></p>' : '') +
                (venuegps.transport ? '<p>' + escapeHtml(venuegps.transport) + '</p>' : '') +
            '</div>' +

            (venuegps.bot_url
                ? '<div class="bns-venuegps__help">' +
                    '<h4>📲 ' + escapeHtml(venuegps.assist_title || 'Need Any Assistance?') + '</h4>' +
                    '<div class="bns-venuegps__help-grid">' +
                        '<a class="bns-venuegps__help-btn bns-venuegps__help-btn--bot" href="' + escapeAttr(venuegps.bot_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span><em>' + escapeHtml(venuegps.bot_label || 'WhatsApp BOT') + '</em>' + escapeHtml(venuegps.bot_hint || '') + '</span>' +
                        '</a>' +
                    '</div>' +
                  '</div>'
                : '') +

            connectHtml +

            '<div class="bns-venuegps__final">' +
                '<h4>🌟 ' + escapeHtml(venuegps.final_title || 'Final Reminder') + '</h4>' +
                (tips ? '<ul class="bns-venuegps__list">' + tips + '</ul>' : '') +
                (venuegps.closing ? '<em>' + escapeHtml(venuegps.closing) + '</em>' : '') +
                (venuegps.brand ? '<strong class="bns-venuegps__brand">' + escapeHtml(venuegps.brand) + '</strong>' : '') +
                (motto ? '<div class="bns-venuegps__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderWelcome(welcome) {
        if (!welcome) {
            return '';
        }

        function iconList(items, className) {
            return (items || []).map(function (item) {
                return '<li class="' + className + '">' +
                    '<span aria-hidden="true">' + escapeHtml(item.icon || '✨') + '</span>' +
                    '<span>' + escapeHtml(item.text || '') + '</span>' +
                    '</li>';
            }).join('');
        }

        var awaits = iconList(welcome.awaits, 'bns-welcome__note');
        var before = iconList(welcome.before, 'bns-welcome__note');
        var participate = iconList(welcome.participate, 'bns-welcome__note');

        var partners = (welcome.partners || []).map(function (partner) {
            return '<div class="bns-welcome__partner">' +
                '<span>' + escapeHtml(partner.label || 'Partner') + '</span>' +
                '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                '</div>';
        }).join('');

        var thoughtActions = (welcome.thought_actions || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        var motto = (welcome.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-welcome">' +
            '<div class="bns-welcome__hero">' +
                '<span class="bns-welcome__hero-badge">' + escapeHtml(welcome.eyebrow || 'Seminar Welcome') + '</span>' +
                '<div class="bns-welcome__hero-icon" aria-hidden="true"><i class="fas fa-sun"></i></div>' +
                '<h3>🌞 ' + escapeHtml(welcome.headline || 'Good Morning & Welcome!') + '</h3>' +
                (welcome.intro ? '<p class="bns-welcome__intro">' + escapeHtml(welcome.intro) + '</p>' : '') +
                (welcome.thanks ? '<p class="bns-welcome__hero-sub">' + escapeHtml(welcome.thanks) + '</p>' : '') +
                (welcome.journey ? '<strong class="bns-welcome__journey">' + escapeHtml(welcome.journey) + '</strong>' : '') +
            '</div>' +

            (awaits
                ? '<div class="bns-welcome__card">' +
                    '<h4><span>🌟 ' + escapeHtml(welcome.awaits_title || 'What Awaits You Today?') + '</span></h4>' +
                    '<ul class="bns-welcome__notes bns-welcome__notes--2">' + awaits + '</ul>' +
                  '</div>'
                : '') +

            (before
                ? '<div class="bns-welcome__card">' +
                    '<h4><span>📢 ' + escapeHtml(welcome.before_title || 'Before We Begin') + '</span></h4>' +
                    '<ul class="bns-welcome__notes">' + before + '</ul>' +
                  '</div>'
                : '') +

            ((participate || welcome.participate_note)
                ? '<div class="bns-welcome__card">' +
                    '<h4><span>✍️ ' + escapeHtml(welcome.participate_title || 'Participate Actively') + '</span></h4>' +
                    (participate ? '<ul class="bns-welcome__notes">' + participate + '</ul>' : '') +
                    (welcome.participate_note ? '<p class="bns-welcome__callout">' + escapeHtml(welcome.participate_note) + '</p>' : '') +
                  '</div>'
                : '') +

            ((welcome.photo || welcome.photo_note)
                ? '<div class="bns-welcome__photo">' +
                    '<h4>📸 ' + escapeHtml(welcome.photo_title || 'During the Seminar') + '</h4>' +
                    (welcome.photo ? '<p>' + escapeHtml(welcome.photo) + '</p>' : '') +
                    (welcome.photo_note ? '<p>' + escapeHtml(welcome.photo_note) + '</p>' : '') +
                  '</div>'
                : '') +

            '<div class="bns-welcome__help">' +
                '<h4>📲 ' + escapeHtml(welcome.help_title || 'Stay Connected') + '</h4>' +
                '<div class="bns-welcome__help-grid">' +
                    (welcome.bot_url
                        ? '<a class="bns-welcome__help-btn bns-welcome__help-btn--bot" href="' + escapeAttr(welcome.bot_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span><em>' + escapeHtml(welcome.bot_label || 'WhatsApp BOT') + '</em>' + escapeHtml(welcome.bot_hint || '') + '</span>' +
                          '</a>'
                        : '') +
                    (welcome.channel_url
                        ? '<a class="bns-welcome__help-btn bns-welcome__help-btn--channel" href="' + escapeAttr(welcome.channel_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(welcome.channel_title || 'WhatsApp Channel') + '</span>' +
                          '</a>'
                        : '') +
                    (welcome.website
                        ? '<a class="bns-welcome__help-btn bns-welcome__help-btn--web" href="' + escapeAttr(welcome.website) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-globe" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(welcome.website.replace(/^https?:\/\//, '')) + '</span>' +
                          '</a>'
                        : '') +
                '</div>' +
            '</div>' +

            (partners ? '<div class="bns-welcome__partners">' + partners + '</div>' : '') +

            '<div class="bns-welcome__thought">' +
                '<span class="bns-welcome__thought-label">' + escapeHtml(welcome.thought_title || 'Today\'s Thought') + '</span>' +
                (welcome.thought_quote ? '<strong class="bns-welcome__quote">' + escapeHtml(welcome.thought_quote) + '</strong>' : '') +
                (welcome.thought ? '<p>' + escapeHtml(welcome.thought) + '</p>' : '') +
                (thoughtActions ? '<div class="bns-welcome__thought-actions">' + thoughtActions + '</div>' : '') +
            '</div>' +

            '<div class="bns-welcome__final">' +
                (welcome.wish ? '<p>' + escapeHtml(welcome.wish) + '</p>' : '') +
                (welcome.closing ? '<strong class="bns-welcome__closing">' + escapeHtml(welcome.closing) + '</strong>' : '') +
                (motto ? '<div class="bns-welcome__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderReminder(reminder) {
        if (!reminder) {
            return '';
        }

        var venue = reminder.venue || {};
        var venueLines = (venue.lines || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        function checkList(items) {
            return (items || []).map(function (text) {
                return '<li class="bns-reminder__item">' +
                    '<span class="bns-reminder__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                    '<strong>' + escapeHtml(text) + '</strong>' +
                    '</li>';
            }).join('');
        }

        function iconList(items) {
            return (items || []).map(function (item) {
                return '<li class="bns-reminder__note">' +
                    '<span aria-hidden="true">' + escapeHtml(item.icon || '📌') + '</span>' +
                    '<span>' + escapeHtml(item.text || '') + '</span>' +
                    '</li>';
            }).join('');
        }

        var checklist = checkList(reminder.checklist);
        var travel = checkList(reminder.travel);
        var dress = checkList(reminder.dress);
        var instructions = iconList(reminder.instructions);
        var make = iconList(reminder.make);

        var rsvp = (reminder.rsvp || []).map(function (text, index) {
            var icon = index === 0 ? '🚗' : '📍';
            return '<strong class="bns-reminder__rsvp-btn">' + icon + ' ' + escapeHtml(text) + '</strong>';
        }).join('<span class="bns-reminder__rsvp-or">OR</span>');

        var partners = reminder.partners || [];
        var partnersHtml = partners.length
            ? '<div class="bns-reminder__partners">' +
                partners.map(function (partner) {
                    return '<div class="bns-reminder__partner">' +
                        '<span>' + escapeHtml(partner.label || 'Partner') + '</span>' +
                        '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                    '</div>';
                }).join('') +
              '</div>'
            : '';

        var actions = (reminder.actions || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        var motto = (reminder.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        var sessions = reminder.sessions || [];
        var eventHtml = '';
        if (sessions.length) {
            eventHtml = '<div class="bns-reminder__sessions">' +
                sessions.map(function (session) {
                    var tone = session.tone === 'blue' ? 'blue' : 'green';
                    return '<div class="bns-reminder__session bns-reminder__session--' + tone + '">' +
                        '<strong class="bns-reminder__session-label">' + escapeHtml(session.label || 'Session') + '</strong>' +
                        '<div class="bns-reminder__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(session.date || '') + '</strong></div></div>' +
                        '<div class="bns-reminder__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(session.time || '') + '</strong></div></div>' +
                        (session.reporting
                            ? '<div class="bns-reminder__event-row bns-reminder__event-row--accent bns-reminder__event-row--full"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(session.reporting) + '</strong></div></div>'
                            : '') +
                    '</div>';
                }).join('') +
            '</div>';
        } else {
            eventHtml = '<div class="bns-reminder__event">' +
                '<div class="bns-reminder__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(reminder.date || '') + '</strong></div></div>' +
                '<div class="bns-reminder__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(reminder.time || '') + '</strong></div></div>' +
                '<div class="bns-reminder__event-row bns-reminder__event-row--accent"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(reminder.report_time || '') + '</strong></div></div>' +
            '</div>';
        }

        return '<div class="bns-reminder">' +
            '<div class="bns-reminder__hero">' +
                '<span class="bns-reminder__hero-badge">' + escapeHtml(reminder.eyebrow || 'Last Call') + '</span>' +
                '<div class="bns-reminder__hero-icon" aria-hidden="true"><i class="fas fa-bell"></i></div>' +
                '<h3>🌟 ' + escapeHtml(reminder.headline || 'Final Reminder') + '</h3>' +
                (reminder.greeting ? '<p class="bns-reminder__greeting">' + escapeHtml(reminder.greeting) + '</p>' : '') +
                (reminder.intro ? '<p class="bns-reminder__hero-sub">' + escapeHtml(reminder.intro) + '</p>' : '') +
                (reminder.thanks ? '<p class="bns-reminder__hero-sub">' + escapeHtml(reminder.thanks) + '</p>' : '') +
            '</div>' +

            eventHtml +

            (venue.title || venueLines
                ? '<div class="bns-reminder__card">' +
                    '<h4><span>📍 Venue</span></h4>' +
                    (venue.title ? '<strong class="bns-reminder__venue-title">' + escapeHtml(venue.title) + '</strong>' : '') +
                    (venueLines ? '<ul class="bns-reminder__venue-lines">' + venueLines + '</ul>' : '') +
                    (venue.maps_url
                        ? '<a class="bns-reminder__maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS Location' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            partnersHtml +

            '<div class="bns-reminder__grid">' +
                (checklist
                    ? '<div class="bns-reminder__card">' +
                        '<h4><span>🎒 ' + escapeHtml(reminder.checklist_title || 'Final Checklist') + '</span></h4>' +
                        '<ul class="bns-reminder__list">' + checklist + '</ul>' +
                      '</div>'
                    : '') +
                (dress
                    ? '<div class="bns-reminder__card">' +
                        '<h4><span>👔 ' + escapeHtml(reminder.dress_title || 'Dress Code') + '</span></h4>' +
                        '<ul class="bns-reminder__list">' + dress + '</ul>' +
                      '</div>'
                    : '') +
            '</div>' +

            ((travel || reminder.seats)
                ? '<div class="bns-reminder__card">' +
                    '<h4><span>🚗 ' + escapeHtml(reminder.travel_title || 'Travel Reminder') + '</span></h4>' +
                    (travel ? '<ul class="bns-reminder__list">' + travel + '</ul>' : '') +
                    (reminder.seats ? '<div class="bns-reminder__seats"><span aria-hidden="true">💺</span><strong>' + escapeHtml(reminder.seats) + '</strong></div>' : '') +
                  '</div>'
                : '') +

            (instructions
                ? '<div class="bns-reminder__card">' +
                    '<h4><span>📢 ' + escapeHtml(reminder.instructions_title || 'Important Instructions') + '</span></h4>' +
                    '<ul class="bns-reminder__notes">' + instructions + '</ul>' +
                  '</div>'
                : '') +

            (make
                ? '<div class="bns-reminder__card">' +
                    '<h4><span>🤝 ' + escapeHtml(reminder.make_title || 'Make the Most of Today') + '</span></h4>' +
                    '<ul class="bns-reminder__notes bns-reminder__notes--2">' + make + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-reminder__help">' +
                '<h4>📲 ' + escapeHtml(reminder.help_title || 'Need Any Help?') + '</h4>' +
                '<div class="bns-reminder__help-grid">' +
                    (reminder.bot_url
                        ? '<a class="bns-reminder__help-btn bns-reminder__help-btn--bot" href="' + escapeAttr(reminder.bot_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span><em>' + escapeHtml(reminder.bot_label || 'WhatsApp BOT') + '</em>' + escapeHtml(reminder.bot_hint || '') + '</span>' +
                          '</a>'
                        : '') +
                    (reminder.channel_url
                        ? '<a class="bns-reminder__help-btn bns-reminder__help-btn--channel" href="' + escapeAttr(reminder.channel_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(reminder.channel_title || 'WhatsApp Channel') + '</span>' +
                          '</a>'
                        : '') +
                    (reminder.website
                        ? '<a class="bns-reminder__help-btn bns-reminder__help-btn--web" href="' + escapeAttr(reminder.website) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-globe" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(reminder.website.replace(/^https?:\/\//, '')) + '</span>' +
                          '</a>'
                        : '') +
                '</div>' +
            '</div>' +

            (reminder.assist
                ? '<div class="bns-reminder__assist">' +
                    '<h4>🆘 ' + escapeHtml(reminder.assist_title || 'Need Assistance?') + '</h4>' +
                    '<p>' + escapeHtml(reminder.assist) + '</p>' +
                  '</div>'
                : '') +

            '<div class="bns-reminder__rsvp">' +
                '<h4>✅ ' + escapeHtml(reminder.rsvp_title || 'Attendance Update') + '</h4>' +
                (reminder.rsvp_intro ? '<p>' + escapeHtml(reminder.rsvp_intro) + '</p>' : '') +
                (rsvp ? '<div class="bns-reminder__rsvp-row">' + rsvp + '</div>' : '') +
            '</div>' +

            '<div class="bns-reminder__final">' +
                '<h4>🌟 ' + escapeHtml(reminder.final_title || 'Final Message') + '</h4>' +
                (reminder.final ? '<strong>' + escapeHtml(reminder.final) + '</strong>' : '') +
                (reminder.final_note ? '<p>' + escapeHtml(reminder.final_note) + '</p>' : '') +
                (actions ? '<div class="bns-reminder__actions">' + actions + '</div>' : '') +
                (reminder.safe ? '<em>' + escapeHtml(reminder.safe) + '</em>' : '') +
                (reminder.closing ? '<p class="bns-reminder__closing">' + escapeHtml(reminder.closing) + '</p>' : '') +
                (reminder.brand ? '<strong class="bns-reminder__brand">' + escapeHtml(reminder.brand) + '</strong>' : '') +
                (motto ? '<div class="bns-reminder__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderToday(today) {
        if (!today) {
            return '';
        }

        var venue = today.venue || {};
        var venueLines = (venue.lines || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        function checkList(items) {
            return (items || []).map(function (text) {
                return '<li class="bns-today__item">' +
                    '<span class="bns-today__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                    '<strong>' + escapeHtml(text) + '</strong>' +
                    '</li>';
            }).join('');
        }

        function iconList(items) {
            return (items || []).map(function (item) {
                return '<li class="bns-today__note">' +
                    '<span aria-hidden="true">' + escapeHtml(item.icon || '📌') + '</span>' +
                    '<span>' + escapeHtml(item.text || '') + '</span>' +
                    '</li>';
            }).join('');
        }

        var leave = checkList(today.leave);
        var carry = checkList(today.carry);
        var dress = checkList(today.dress);
        var reg = checkList(today.reg);
        var network = checkList(today.network);
        var learn = iconList(today.learn);
        var instructions = iconList(today.instructions);

        var motto = (today.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-today">' +
            '<div class="bns-today__hero">' +
                '<span class="bns-today__hero-badge">' + escapeHtml(today.eyebrow || 'Seminar Day') + '</span>' +
                '<div class="bns-today__hero-icon" aria-hidden="true"><i class="fas fa-flag-checkered"></i></div>' +
                '<h3>🌟 ' + escapeHtml(today.headline || 'Today Is The Day!') + '</h3>' +
                (today.greeting ? '<p class="bns-today__greeting">' + escapeHtml(today.greeting) + '</p>' : '') +
                (today.hook ? '<p class="bns-today__hook">' + escapeHtml(today.hook) + '</p>' : '') +
                (today.intro ? '<p class="bns-today__hero-sub">' + escapeHtml(today.intro) + '</p>' : '') +
                (today.welcome ? '<p class="bns-today__hero-sub">' + escapeHtml(today.welcome) + '</p>' : '') +
            '</div>' +

            '<div class="bns-today__event">' +
                '<div class="bns-today__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(today.date || '') + '</strong></div></div>' +
                '<div class="bns-today__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(today.time || '') + '</strong></div></div>' +
                '<div class="bns-today__event-row bns-today__event-row--accent"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(today.report_time || '') + '</strong></div></div>' +
            '</div>' +

            (venue.title || venueLines
                ? '<div class="bns-today__card">' +
                    '<h4><span>📍 Venue</span></h4>' +
                    (venue.title ? '<strong class="bns-today__venue-title">' + escapeHtml(venue.title) + '</strong>' : '') +
                    (venueLines ? '<ul class="bns-today__venue-lines">' + venueLines + '</ul>' : '') +
                    (venue.maps_url
                        ? '<a class="bns-today__maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS Location' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            ((leave || today.seats)
                ? '<div class="bns-today__card">' +
                    '<h4><span>🚗 ' + escapeHtml(today.leave_title || 'Before You Leave') + '</span></h4>' +
                    (leave ? '<ul class="bns-today__list">' + leave + '</ul>' : '') +
                    (today.seats ? '<div class="bns-today__seats"><span aria-hidden="true">💺</span><strong>' + escapeHtml(today.seats) + '</strong></div>' : '') +
                  '</div>'
                : '') +

            '<div class="bns-today__grid">' +
                (carry
                    ? '<div class="bns-today__card">' +
                        '<h4><span>🎒 ' + escapeHtml(today.carry_title || 'Please Carry') + '</span></h4>' +
                        '<ul class="bns-today__list">' + carry + '</ul>' +
                      '</div>'
                    : '') +
                (dress
                    ? '<div class="bns-today__card">' +
                        '<h4><span>👔 ' + escapeHtml(today.dress_title || 'Dress Code') + '</span></h4>' +
                        '<ul class="bns-today__list">' + dress + '</ul>' +
                      '</div>'
                    : '') +
            '</div>' +

            (learn
                ? '<div class="bns-today__card">' +
                    '<h4><span>🎯 ' + escapeHtml(today.learn_title || 'Today\'s Learning') + '</span></h4>' +
                    '<ul class="bns-today__notes bns-today__notes--2">' + learn + '</ul>' +
                  '</div>'
                : '') +

            (instructions
                ? '<div class="bns-today__card">' +
                    '<h4><span>📢 ' + escapeHtml(today.instructions_title || 'Important Instructions') + '</span></h4>' +
                    '<ul class="bns-today__notes">' + instructions + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-today__grid">' +
                (reg
                    ? '<div class="bns-today__card">' +
                        '<h4><span>✅ ' + escapeHtml(today.reg_title || 'Registration') + '</span></h4>' +
                        '<ul class="bns-today__list">' + reg + '</ul>' +
                      '</div>'
                    : '') +
                ((network || today.network_note)
                    ? '<div class="bns-today__card">' +
                        '<h4><span>🤝 ' + escapeHtml(today.network_title || 'Network & Connect') + '</span></h4>' +
                        (network ? '<ul class="bns-today__list">' + network + '</ul>' : '') +
                        (today.network_note ? '<p class="bns-today__network-note">' + escapeHtml(today.network_note) + '</p>' : '') +
                      '</div>'
                    : '') +
            '</div>' +

            '<div class="bns-today__help">' +
                '<h4>📲 ' + escapeHtml(today.help_title || 'Need Any Help?') + '</h4>' +
                '<div class="bns-today__help-grid">' +
                    (today.bot_url
                        ? '<a class="bns-today__help-btn bns-today__help-btn--bot" href="' + escapeAttr(today.bot_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span><em>' + escapeHtml(today.bot_label || 'WhatsApp BOT') + '</em>' + escapeHtml(today.bot_hint || '') + '</span>' +
                          '</a>'
                        : '') +
                    (today.channel_url
                        ? '<a class="bns-today__help-btn bns-today__help-btn--channel" href="' + escapeAttr(today.channel_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(today.channel_title || 'WhatsApp Channel') + '</span>' +
                          '</a>'
                        : '') +
                    (today.website
                        ? '<a class="bns-today__help-btn bns-today__help-btn--web" href="' + escapeAttr(today.website) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-globe" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(today.website.replace(/^https?:\/\//, '')) + '</span>' +
                          '</a>'
                        : '') +
                '</div>' +
            '</div>' +

            '<div class="bns-today__rsvp">' +
                '<h4>✅ ' + escapeHtml(today.rsvp_title || 'Attendance Confirmation') + '</h4>' +
                (today.rsvp_intro ? '<p>' + escapeHtml(today.rsvp_intro) + '</p>' : '') +
                (today.rsvp ? '<strong class="bns-today__rsvp-btn">🚗 ' + escapeHtml(today.rsvp) + '</strong>' : '') +
                (today.rsvp_note ? '<em>' + escapeHtml(today.rsvp_note) + '</em>' : '') +
            '</div>' +

            '<div class="bns-today__final">' +
                '<h4>🌟 ' + escapeHtml(today.final_title || 'Final Message') + '</h4>' +
                (today.final ? '<strong>' + escapeHtml(today.final) + '</strong>' : '') +
                (today.final_note ? '<p>' + escapeHtml(today.final_note) + '</p>' : '') +
                (today.final_tagline ? '<p class="bns-today__tagline">' + escapeHtml(today.final_tagline) + '</p>' : '') +
                (today.safe ? '<em>' + escapeHtml(today.safe) + '</em>' : '') +
                (today.closing ? '<p class="bns-today__closing">' + escapeHtml(today.closing) + '</p>' : '') +
                (motto ? '<div class="bns-today__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderFounder(founder) {
        if (!founder) {
            return '';
        }

        var vision = (founder.vision || []).map(function (text) {
            return '<li class="bns-founder__item">' +
                '<span class="bns-founder__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var experience = (founder.experience || []).map(function (text) {
            return '<li class="bns-founder__item">' +
                '<span class="bns-founder__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var gujarati = (founder.gujarati || []).map(function (text, index) {
            var isAccent = index === 0 || index === 1 || index === 3 || index === 5;
            return '<p class="bns-founder__guj-line' + (isAccent ? ' bns-founder__guj-line--accent' : '') + '">' + escapeHtml(text) + '</p>';
        }).join('');

        var highlights = (founder.highlights || []).map(function (text) {
            return '<li class="bns-founder__item">' +
                '<span class="bns-founder__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var venue = founder.venue || {};
        var venueLines = (venue.lines || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        var motto = (founder.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        var sessions = founder.sessions || [];
        var eventHtml = '';
        if (sessions.length) {
            eventHtml = '<div class="bns-founder__sessions">' +
                sessions.map(function (session, index) {
                    var tone = session.tone === 'blue' ? 'blue' : 'green';
                    var orDivider = (session.or || index > 0)
                        ? '<div class="bns-founder__or"><span>OR</span></div>'
                        : '';
                    return orDivider +
                        '<div class="bns-founder__session bns-founder__session--' + tone + '">' +
                            '<strong class="bns-founder__session-label">' + escapeHtml(session.label || 'Session') + '</strong>' +
                            '<div class="bns-founder__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(session.date || '') + '</strong></div></div>' +
                            '<div class="bns-founder__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(session.time || '') + '</strong></div></div>' +
                            (session.reporting
                                ? '<div class="bns-founder__event-row bns-founder__event-row--full"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(session.reporting) + '</strong></div></div>'
                                : '') +
                        '</div>';
                }).join('') +
            '</div>';
        } else {
            eventHtml = '<div class="bns-founder__event">' +
                '<div class="bns-founder__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(founder.date || '') + '</strong></div></div>' +
                '<div class="bns-founder__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Time</span><strong>' + escapeHtml(founder.time || '') + '</strong></div></div>' +
            '</div>';
        }

        var partners = founder.partners || [];
        var partnersHtml = partners.length
            ? '<div class="bns-founder__partners">' +
                partners.map(function (partner) {
                    return '<div class="bns-founder__partner">' +
                        '<span>' + escapeHtml(partner.label || '') + '</span>' +
                        '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                    '</div>';
                }).join('') +
              '</div>'
            : '';

        return '<div class="bns-founder">' +
            '<div class="bns-founder__hero">' +
                '<span class="bns-founder__hero-badge">' + escapeHtml(founder.eyebrow || 'From the Founder') + '</span>' +
                '<div class="bns-founder__hero-icon" aria-hidden="true"><i class="fas fa-handshake"></i></div>' +
                '<h3>🌟 ' + escapeHtml(founder.headline || 'A Personal Invitation from the Founder') + '</h3>' +
                (founder.greeting ? '<p class="bns-founder__greeting">' + escapeHtml(founder.greeting) + '</p>' : '') +
                '<p class="bns-founder__hero-sub">' + escapeHtml(founder.intro || '') + '</p>' +
            '</div>' +

            '<div class="bns-founder__mission">' +
                '<p>' + escapeHtml(founder.mission || '') + '</p>' +
                (founder.vision_title ? '<strong class="bns-founder__vision-title">' + escapeHtml(founder.vision_title) + '</strong>' : '') +
                (vision ? '<ul class="bns-founder__list">' + vision + '</ul>' : '') +
            '</div>' +

            (experience
                ? '<div class="bns-founder__card">' +
                    '<h4><span>🌟 ' + escapeHtml(founder.experience_title || 'In this Seminar, you will experience:') + '</span></h4>' +
                    '<ul class="bns-founder__list">' + experience + '</ul>' +
                  '</div>'
                : '') +

            (gujarati
                ? '<div class="bns-founder__gujarati">' +
                    (founder.gujarati_title ? '<h4>' + escapeHtml(founder.gujarati_title) + '</h4>' : '') +
                    gujarati +
                  '</div>'
                : '') +

            (founder.journey
                ? '<div class="bns-founder__journey"><p>' + escapeHtml(founder.journey) + '</p></div>'
                : '') +

            (highlights
                ? '<div class="bns-founder__card">' +
                    '<h4><span>📚 ' + escapeHtml(founder.highlights_title || 'Seminar Highlights') + '</span></h4>' +
                    '<ul class="bns-founder__list">' + highlights + '</ul>' +
                    (founder.certificate
                        ? '<div class="bns-founder__cert"><span aria-hidden="true">🏆</span><strong>' + escapeHtml(founder.certificate) + '</strong></div>'
                        : '') +
                  '</div>'
                : '') +

            eventHtml +

            (venue.title || venueLines
                ? '<div class="bns-founder__card">' +
                    '<h4><span>📍 Venue</span></h4>' +
                    (venue.title ? '<strong class="bns-founder__venue-title">' + escapeHtml(venue.title) + '</strong>' : '') +
                    (venueLines ? '<ul class="bns-founder__venue-lines">' + venueLines + '</ul>' : '') +
                    (venue.maps_url
                        ? '<a class="bns-founder__maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS Location' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            partnersHtml +

            '<div class="bns-founder__signoff">' +
                (founder.closing ? '<p>' + escapeHtml(founder.closing) + '</p>' : '') +
                (founder.closing_note ? '<p>' + escapeHtml(founder.closing_note) + '</p>' : '') +
                (founder.session_choice ? '<p class="bns-founder__choice">' + escapeHtml(founder.session_choice) + '</p>' : '') +
                (founder.regards ? '<em>' + escapeHtml(founder.regards) + '</em>' : '') +
                '<div class="bns-founder__signature">' +
                    (founder.name ? '<strong>' + escapeHtml(founder.name) + '</strong>' : '') +
                    (founder.role ? '<span>' + escapeHtml(founder.role) + '</span>' : '') +
                    (founder.brand ? '<span>' + escapeHtml(founder.brand) + '</span>' : '') +
                    (founder.mobile
                        ? '<a class="bns-founder__mobile" href="https://wa.me/' + escapeAttr(String(founder.mobile).replace(/\D/g, '')) + '?text=Hello" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i> ' +
                            escapeHtml(founder.mobile_label || 'Mobile & WhatsApp') + ': ' + escapeHtml(founder.mobile) +
                          '</a>'
                        : '') +
                '</div>' +
                (motto ? '<div class="bns-founder__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderChecklist(checklist) {
        if (!checklist) {
            return '';
        }

        var venue = checklist.venue || {};
        var venueLines = (venue.lines || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        function checkList(items) {
            return (items || []).map(function (text) {
                return '<li class="bns-checklist__item">' +
                    '<span class="bns-checklist__box" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                    '<strong>' + escapeHtml(text) + '</strong>' +
                    '</li>';
            }).join('');
        }

        function iconList(items, className) {
            return (items || []).map(function (item) {
                return '<li class="' + className + '">' +
                    '<span aria-hidden="true">' + escapeHtml(item.icon || '📌') + '</span>' +
                    '<span>' + escapeHtml(item.text || '') + '</span>' +
                    '</li>';
            }).join('');
        }

        var home = checkList(checklist.home);
        var carry = checkList(checklist.carry);
        var dress = checkList(checklist.dress);
        var during = iconList(checklist.during, 'bns-checklist__note');
        var info = iconList(checklist.info, 'bns-checklist__note');

        var motto = (checklist.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        var sessions = checklist.sessions || [];
        var eventHtml = '';
        if (sessions.length) {
            eventHtml = '<div class="bns-checklist__sessions">' +
                sessions.map(function (session) {
                    var tone = session.tone === 'blue' ? 'blue' : 'green';
                    return '<div class="bns-checklist__session bns-checklist__session--' + tone + '">' +
                        '<strong class="bns-checklist__session-label">' + escapeHtml(session.label || 'Session') + '</strong>' +
                        '<div class="bns-checklist__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(session.date || '') + '</strong></div></div>' +
                        '<div class="bns-checklist__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(session.time || '') + '</strong></div></div>' +
                        (session.reporting
                            ? '<div class="bns-checklist__event-row bns-checklist__event-row--accent bns-checklist__event-row--full"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(session.reporting) + '</strong></div></div>'
                            : '') +
                    '</div>';
                }).join('') +
            '</div>';
        } else {
            eventHtml = '<div class="bns-checklist__event">' +
                '<div class="bns-checklist__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(checklist.date || '') + '</strong></div></div>' +
                '<div class="bns-checklist__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(checklist.time || '') + '</strong></div></div>' +
                '<div class="bns-checklist__event-row bns-checklist__event-row--accent"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(checklist.report_time || '') + '</strong></div></div>' +
            '</div>';
        }

        var partners = checklist.partners || [];
        var partnersHtml = partners.length
            ? '<div class="bns-checklist__partners">' +
                partners.map(function (partner) {
                    return '<div class="bns-checklist__partner">' +
                        '<span>' + escapeHtml(partner.label || '') + '</span>' +
                        '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                    '</div>';
                }).join('') +
              '</div>'
            : '';

        return '<div class="bns-checklist">' +
            '<div class="bns-checklist__hero">' +
                '<span class="bns-checklist__hero-badge">' + escapeHtml(checklist.eyebrow || 'Ready to Go') + '</span>' +
                '<div class="bns-checklist__hero-icon" aria-hidden="true"><i class="fas fa-clipboard-check"></i></div>' +
                '<h3>✅ ' + escapeHtml(checklist.headline || 'Final Checklist') + '</h3>' +
                (checklist.tagline ? '<p class="bns-checklist__tagline">' + escapeHtml(checklist.tagline) + '</p>' : '') +
                (checklist.intro ? '<p class="bns-checklist__hero-sub">' + escapeHtml(checklist.intro) + '</p>' : '') +
                (checklist.intro_note ? '<p class="bns-checklist__hero-sub">' + escapeHtml(checklist.intro_note) + '</p>' : '') +
            '</div>' +

            eventHtml +

            (venue.title || venueLines
                ? '<div class="bns-checklist__card">' +
                    '<h4><span>📍 Venue</span></h4>' +
                    (venue.title ? '<strong class="bns-checklist__venue-title">' + escapeHtml(venue.title) + '</strong>' : '') +
                    (venueLines ? '<ul class="bns-checklist__venue-lines">' + venueLines + '</ul>' : '') +
                    (venue.maps_url
                        ? '<a class="bns-checklist__maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS Location' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            partnersHtml +

            (home
                ? '<div class="bns-checklist__card bns-checklist__card--home">' +
                    '<h4><span>🏠 ' + escapeHtml(checklist.home_title || 'Before You Leave Home') + '</span></h4>' +
                    '<ul class="bns-checklist__list">' + home + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-checklist__grid">' +
                (carry
                    ? '<div class="bns-checklist__card">' +
                        '<h4><span>🎒 ' + escapeHtml(checklist.carry_title || 'Please Carry') + '</span></h4>' +
                        '<ul class="bns-checklist__list">' + carry + '</ul>' +
                      '</div>'
                    : '') +
                (dress
                    ? '<div class="bns-checklist__card">' +
                        '<h4><span>👔 ' + escapeHtml(checklist.dress_title || 'Dress Code') + '</span></h4>' +
                        '<ul class="bns-checklist__list">' + dress + '</ul>' +
                      '</div>'
                    : '') +
            '</div>' +

            (during
                ? '<div class="bns-checklist__card">' +
                    '<h4><span>📢 ' + escapeHtml(checklist.during_title || 'During the Seminar') + '</span></h4>' +
                    '<ul class="bns-checklist__notes">' + during + '</ul>' +
                  '</div>'
                : '') +

            (info
                ? '<div class="bns-checklist__card">' +
                    '<h4><span>ℹ️ ' + escapeHtml(checklist.info_title || 'Important Information') + '</span></h4>' +
                    '<ul class="bns-checklist__notes">' + info + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-checklist__help">' +
                '<h4>📲 ' + escapeHtml(checklist.help_title || 'Need Any Help?') + '</h4>' +
                '<div class="bns-checklist__help-grid">' +
                    (checklist.bot_url
                        ? '<a class="bns-checklist__help-btn bns-checklist__help-btn--bot" href="' + escapeAttr(checklist.bot_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span><em>' + escapeHtml(checklist.bot_label || 'WhatsApp BOT') + '</em>' + escapeHtml(checklist.bot_hint || '') + '</span>' +
                          '</a>'
                        : '') +
                    (checklist.channel_url
                        ? '<a class="bns-checklist__help-btn bns-checklist__help-btn--channel" href="' + escapeAttr(checklist.channel_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(checklist.channel_title || 'WhatsApp Channel') + '</span>' +
                          '</a>'
                        : '') +
                    (checklist.website
                        ? '<a class="bns-checklist__help-btn bns-checklist__help-btn--web" href="' + escapeAttr(checklist.website) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-globe" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(checklist.website.replace(/^https?:\/\//, '')) + '</span>' +
                          '</a>'
                        : '') +
                '</div>' +
            '</div>' +

            '<div class="bns-checklist__rsvp">' +
                '<h4>✅ ' + escapeHtml(checklist.rsvp_title || 'Attendance Confirmation') + '</h4>' +
                (checklist.rsvp_intro ? '<p>' + escapeHtml(checklist.rsvp_intro) + '</p>' : '') +
                (checklist.rsvp ? '<strong class="bns-checklist__rsvp-btn">✅ ' + escapeHtml(checklist.rsvp) + '</strong>' : '') +
                (checklist.rsvp_note ? '<em>' + escapeHtml(checklist.rsvp_note) + '</em>' : '') +
            '</div>' +

            '<div class="bns-checklist__final">' +
                '<h4>🚀 ' + escapeHtml(checklist.final_title || 'Final Message') + '</h4>' +
                (checklist.final ? '<strong>' + escapeHtml(checklist.final) + '</strong>' : '') +
                (checklist.final_note ? '<p>' + escapeHtml(checklist.final_note) + '</p>' : '') +
                (checklist.final_welcome ? '<p>' + escapeHtml(checklist.final_welcome) + '</p>' : '') +
                (checklist.closing ? '<em>' + escapeHtml(checklist.closing) + '</em>' : '') +
                (checklist.brand ? '<strong class="bns-checklist__brand">' + escapeHtml(checklist.brand) + '</strong>' : '') +
                (motto ? '<div class="bns-checklist__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderTomorrow(tomorrow) {
        if (!tomorrow) {
            return '';
        }

        var venue = tomorrow.venue || {};
        var venueLines = (venue.lines || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        function checkList(items) {
            return (items || []).map(function (text) {
                return '<li class="bns-tomorrow__item">' +
                    '<span class="bns-tomorrow__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                    '<strong>' + escapeHtml(text) + '</strong>' +
                    '</li>';
            }).join('');
        }

        var early = checkList(tomorrow.early);
        var dress = checkList(tomorrow.dress);
        var bring = checkList(tomorrow.bring);
        var learn = checkList(tomorrow.learn);

        var instructions = (tomorrow.instructions || []).map(function (item) {
            return '<li class="bns-tomorrow__note">' +
                '<span class="bns-tomorrow__note-icon" aria-hidden="true">' + escapeHtml(item.icon || '📌') + '</span>' +
                '<span>' + escapeHtml(item.text || '') + '</span>' +
                '</li>';
        }).join('');

        var motto = (tomorrow.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        var sessions = tomorrow.sessions || [];
        var eventHtml = '';
        if (sessions.length) {
            eventHtml = '<div class="bns-tomorrow__sessions">' +
                sessions.map(function (session) {
                    var tone = session.tone === 'blue' ? 'blue' : 'green';
                    return '<div class="bns-tomorrow__session bns-tomorrow__session--' + tone + '">' +
                        '<strong class="bns-tomorrow__session-label">' + escapeHtml(session.label || 'Session') + '</strong>' +
                        '<div class="bns-tomorrow__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(session.date || '') + '</strong></div></div>' +
                        '<div class="bns-tomorrow__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(session.time || '') + '</strong></div></div>' +
                        (session.reporting
                            ? '<div class="bns-tomorrow__event-row bns-tomorrow__event-row--accent bns-tomorrow__event-row--full"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(session.reporting) + '</strong></div></div>'
                            : '') +
                    '</div>';
                }).join('') +
            '</div>';
        } else {
            eventHtml = '<div class="bns-tomorrow__event">' +
                '<div class="bns-tomorrow__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(tomorrow.date || '') + '</strong></div></div>' +
                '<div class="bns-tomorrow__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(tomorrow.time || '') + '</strong></div></div>' +
                '<div class="bns-tomorrow__event-row bns-tomorrow__event-row--accent"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(tomorrow.report_time || '') + '</strong></div></div>' +
            '</div>';
        }

        var partners = tomorrow.partners || [];
        var partnersHtml = partners.length
            ? '<div class="bns-tomorrow__partners">' +
                partners.map(function (partner) {
                    return '<div class="bns-tomorrow__partner">' +
                        '<span>' + escapeHtml(partner.label || '') + '</span>' +
                        '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                    '</div>';
                }).join('') +
              '</div>'
            : '';

        return '<div class="bns-tomorrow">' +
            '<div class="bns-tomorrow__hero">' +
                '<span class="bns-tomorrow__hero-badge">' + escapeHtml(tomorrow.eyebrow || 'Final Day Prep') + '</span>' +
                '<div class="bns-tomorrow__hero-icon" aria-hidden="true"><i class="fas fa-sun"></i></div>' +
                '<h3>🌟 ' + escapeHtml(tomorrow.headline || 'Tomorrow Is The Day!') + '</h3>' +
                (tomorrow.tagline ? '<p class="bns-tomorrow__tagline">' + escapeHtml(tomorrow.tagline) + '</p>' : '') +
                (tomorrow.greeting ? '<p class="bns-tomorrow__greeting">' + escapeHtml(tomorrow.greeting) + '</p>' : '') +
                (tomorrow.thanks ? '<p class="bns-tomorrow__hero-sub">' + escapeHtml(tomorrow.thanks) + '</p>' : '') +
                (tomorrow.welcome ? '<p class="bns-tomorrow__hero-sub">' + escapeHtml(tomorrow.welcome) + '</p>' : '') +
            '</div>' +

            eventHtml +

            (venue.title || venueLines
                ? '<div class="bns-tomorrow__card">' +
                    '<h4><span>📍 Venue</span></h4>' +
                    (venue.title ? '<strong class="bns-tomorrow__venue-title">' + escapeHtml(venue.title) + '</strong>' : '') +
                    (venueLines ? '<ul class="bns-tomorrow__venue-lines">' + venueLines + '</ul>' : '') +
                    (venue.maps_url
                        ? '<a class="bns-tomorrow__maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS Location' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            partnersHtml +

            ((early || tomorrow.seats)
                ? '<div class="bns-tomorrow__card">' +
                    '<h4><span>⏰ ' + escapeHtml(tomorrow.early_title || 'Please Arrive Early') + '</span></h4>' +
                    (early ? '<ul class="bns-tomorrow__list">' + early + '</ul>' : '') +
                    (tomorrow.seats ? '<div class="bns-tomorrow__seats"><span aria-hidden="true">💺</span><strong>' + escapeHtml(tomorrow.seats) + '</strong></div>' : '') +
                  '</div>'
                : '') +

            '<div class="bns-tomorrow__grid">' +
                (dress
                    ? '<div class="bns-tomorrow__card">' +
                        '<h4><span>👔 ' + escapeHtml(tomorrow.dress_title || 'Dress Code') + '</span></h4>' +
                        '<ul class="bns-tomorrow__list">' + dress + '</ul>' +
                      '</div>'
                    : '') +
                (bring
                    ? '<div class="bns-tomorrow__card">' +
                        '<h4><span>🎒 ' + escapeHtml(tomorrow.bring_title || 'Please Bring') + '</span></h4>' +
                        '<ul class="bns-tomorrow__list">' + bring + '</ul>' +
                      '</div>'
                    : '') +
            '</div>' +

            (learn
                ? '<div class="bns-tomorrow__card">' +
                    '<h4><span>🎯 ' + escapeHtml(tomorrow.learn_title || 'What Will You Learn?') + '</span></h4>' +
                    '<ul class="bns-tomorrow__list bns-tomorrow__list--2">' + learn + '</ul>' +
                  '</div>'
                : '') +

            (instructions
                ? '<div class="bns-tomorrow__card">' +
                    '<h4><span>📢 ' + escapeHtml(tomorrow.instructions_title || 'Important Instructions') + '</span></h4>' +
                    '<ul class="bns-tomorrow__notes">' + instructions + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-tomorrow__help">' +
                '<h4>📲 ' + escapeHtml(tomorrow.help_title || 'Need Any Help?') + '</h4>' +
                '<div class="bns-tomorrow__help-grid">' +
                    (tomorrow.bot_url
                        ? '<a class="bns-tomorrow__help-btn bns-tomorrow__help-btn--bot" href="' + escapeAttr(tomorrow.bot_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span><em>' + escapeHtml(tomorrow.bot_label || 'WhatsApp BOT') + '</em>' + escapeHtml(tomorrow.bot_hint || '') + '</span>' +
                          '</a>'
                        : '') +
                    (tomorrow.channel_url
                        ? '<a class="bns-tomorrow__help-btn bns-tomorrow__help-btn--channel" href="' + escapeAttr(tomorrow.channel_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fab fa-whatsapp" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(tomorrow.channel_title || 'WhatsApp Channel') + '</span>' +
                          '</a>'
                        : '') +
                    (tomorrow.website
                        ? '<a class="bns-tomorrow__help-btn bns-tomorrow__help-btn--web" href="' + escapeAttr(tomorrow.website) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-globe" aria-hidden="true"></i>' +
                            '<span>' + escapeHtml(tomorrow.website.replace(/^https?:\/\//, '')) + '</span>' +
                          '</a>'
                        : '') +
                '</div>' +
            '</div>' +

            '<div class="bns-tomorrow__rsvp">' +
                '<h4>✅ ' + escapeHtml(tomorrow.rsvp_title || 'Attendance Confirmation') + '</h4>' +
                (tomorrow.rsvp_intro ? '<p>' + escapeHtml(tomorrow.rsvp_intro) + '</p>' : '') +
                (tomorrow.rsvp ? '<strong class="bns-tomorrow__rsvp-btn">✅ ' + escapeHtml(tomorrow.rsvp) + '</strong>' : '') +
                (tomorrow.rsvp_note ? '<em>' + escapeHtml(tomorrow.rsvp_note) + '</em>' : '') +
            '</div>' +

            '<div class="bns-tomorrow__final">' +
                '<h4>🌟 ' + escapeHtml(tomorrow.final_title || 'Final Message') + '</h4>' +
                (tomorrow.final ? '<strong>' + escapeHtml(tomorrow.final) + '</strong>' : '') +
                (tomorrow.final_note ? '<p>' + escapeHtml(tomorrow.final_note) + '</p>' : '') +
                (tomorrow.final_welcome ? '<p>' + escapeHtml(tomorrow.final_welcome) + '</p>' : '') +
                (tomorrow.closing ? '<em>' + escapeHtml(tomorrow.closing) + '</em>' : '') +
                (tomorrow.brand ? '<strong class="bns-tomorrow__brand">' + escapeHtml(tomorrow.brand) + '</strong>' : '') +
                (motto ? '<div class="bns-tomorrow__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderSurprise(surprise) {
        if (!surprise) {
            return '';
        }

        var teaserLines = (surprise.teaser_lines || []).map(function (text) {
            return '<p class="bns-surprise__teaser-line">' + escapeHtml(text) + '</p>';
        }).join('');

        var waiting = (surprise.waiting || []).map(function (item) {
            return '<li class="bns-surprise__wait">' +
                '<span aria-hidden="true">' + escapeHtml(item.icon || '✨') + '</span>' +
                '<strong>' + escapeHtml(item.text || '') + '</strong>' +
                '</li>';
        }).join('');

        var venue = surprise.venue || {};
        var venueLines = (venue.lines || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        var reminders = (surprise.reminders || []).map(function (text) {
            return '<li class="bns-surprise__reminder">' +
                '<span class="bns-surprise__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var motto = (surprise.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        var sessions = surprise.sessions || [];
        var eventHtml = '';
        if (sessions.length) {
            eventHtml = '<div class="bns-surprise__sessions">' +
                sessions.map(function (session) {
                    var tone = session.tone === 'blue' ? 'blue' : 'green';
                    return '<div class="bns-surprise__session bns-surprise__session--' + tone + '">' +
                        '<strong class="bns-surprise__session-label">' + escapeHtml(session.label || 'Session') + '</strong>' +
                        '<div class="bns-surprise__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(session.date || '') + '</strong></div></div>' +
                        '<div class="bns-surprise__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(session.time || '') + '</strong></div></div>' +
                        (session.reporting
                            ? '<div class="bns-surprise__event-row bns-surprise__event-row--accent bns-surprise__event-row--full"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(session.reporting) + '</strong></div></div>'
                            : '') +
                    '</div>';
                }).join('') +
            '</div>';
        } else {
            eventHtml = '<div class="bns-surprise__event">' +
                '<div class="bns-surprise__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(surprise.date || '') + '</strong></div></div>' +
                '<div class="bns-surprise__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(surprise.time || '') + '</strong></div></div>' +
                '<div class="bns-surprise__event-row bns-surprise__event-row--accent"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(surprise.report_time || '') + '</strong></div></div>' +
            '</div>';
        }

        var partners = surprise.partners || [];
        var partnersHtml = partners.length
            ? '<div class="bns-surprise__partners">' +
                partners.map(function (partner) {
                    return '<div class="bns-surprise__partner">' +
                        '<span>' + escapeHtml(partner.label || '') + '</span>' +
                        '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                    '</div>';
                }).join('') +
              '</div>'
            : '';

        return '<div class="bns-surprise">' +
            '<div class="bns-surprise__hero">' +
                '<span class="bns-surprise__hero-badge">' + escapeHtml(surprise.eyebrow || 'Exclusive Reveal') + '</span>' +
                '<div class="bns-surprise__hero-icon" aria-hidden="true"><i class="fas fa-gift"></i></div>' +
                '<h3>🎉 ' + escapeHtml(surprise.headline || 'Big Surprise Awaits You!') + '</h3>' +
                (surprise.greeting ? '<p class="bns-surprise__greeting">' + escapeHtml(surprise.greeting) + '</p>' : '') +
                '<p class="bns-surprise__hero-sub">' + escapeHtml(surprise.intro || '') + '</p>' +
            '</div>' +

            '<div class="bns-surprise__teaser">' +
                '<h4>🎁 ' + escapeHtml(surprise.teaser_title || 'A Special Surprise Has Been Planned!') + '</h4>' +
                (surprise.teaser ? '<p>' + escapeHtml(surprise.teaser) + '</p>' : '') +
                teaserLines +
                (surprise.teaser_punch ? '<strong class="bns-surprise__punch">' + escapeHtml(surprise.teaser_punch) + '</strong>' : '') +
            '</div>' +

            (waiting
                ? '<div class="bns-surprise__card">' +
                    '<h4><span>🌟 ' + escapeHtml(surprise.waiting_title || 'What Could Be Waiting for You?') + '</span></h4>' +
                    '<ul class="bns-surprise__waits">' + waiting + '</ul>' +
                    (surprise.waiting_note ? '<p class="bns-surprise__wait-note">' + escapeHtml(surprise.waiting_note) + '</p>' : '') +
                  '</div>'
                : '') +

            '<div class="bns-surprise__exclusive">' +
                '<h4>🎯 ' + escapeHtml(surprise.exclusive_title || 'Exclusively for Registered Participants') + '</h4>' +
                (surprise.exclusive ? '<p>' + escapeHtml(surprise.exclusive) + '</p>' : '') +
                (surprise.exclusive_alert
                    ? '<div class="bns-surprise__alert"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i><strong>' + escapeHtml(surprise.exclusive_alert) + '</strong></div>'
                    : '') +
            '</div>' +

            eventHtml +

            (venue.title || venueLines
                ? '<div class="bns-surprise__card">' +
                    '<h4><span>📍 Venue</span></h4>' +
                    (venue.title ? '<strong class="bns-surprise__venue-title">' + escapeHtml(venue.title) + '</strong>' : '') +
                    (venueLines ? '<ul class="bns-surprise__venue-lines">' + venueLines + '</ul>' : '') +
                    (venue.maps_url
                        ? '<a class="bns-surprise__maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS Location' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            partnersHtml +

            (reminders
                ? '<div class="bns-surprise__card">' +
                    '<h4><span>📢 ' + escapeHtml(surprise.reminder_title || 'Important Reminder') + '</span></h4>' +
                    '<ul class="bns-surprise__reminders">' + reminders + '</ul>' +
                  '</div>'
                : '') +

            '<div class="bns-surprise__rsvp">' +
                '<h4>🙋 ' + escapeHtml(surprise.rsvp_title || 'Attendance Confirmation') + '</h4>' +
                (surprise.rsvp_intro ? '<p>' + escapeHtml(surprise.rsvp_intro) + '</p>' : '') +
                (surprise.rsvp ? '<strong class="bns-surprise__rsvp-btn">✅ ' + escapeHtml(surprise.rsvp) + '</strong>' : '') +
                (surprise.rsvp_note ? '<em>' + escapeHtml(surprise.rsvp_note) + '</em>' : '') +
            '</div>' +

            '<div class="bns-surprise__footer">' +
                (surprise.thanks ? '<p>' + escapeHtml(surprise.thanks) + '</p>' : '') +
                (surprise.closing ? '<p class="bns-surprise__closing">' + escapeHtml(surprise.closing) + '</p>' : '') +
                (surprise.brand ? '<strong class="bns-surprise__brand">' + escapeHtml(surprise.brand) + '</strong>' : '') +
                (motto ? '<div class="bns-surprise__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderReporting(reporting) {
        if (!reporting) {
            return '';
        }

        var why = (reporting.why || []).map(function (text) {
            return '<li class="bns-reporting__item">' +
                '<span class="bns-reporting__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var motto = (reporting.motto || []).map(function (item) {
            if (typeof item === 'string') {
                return '<div class="bns-reporting__motto-item"><strong>' + escapeHtml(item) + '</strong></div>';
            }
            return '<div class="bns-reporting__motto-item">' +
                '<span aria-hidden="true">' + escapeHtml(item.icon || '✨') + '</span>' +
                '<strong>' + escapeHtml(item.text || '') + '</strong>' +
                '</div>';
        }).join('');

        var venue = reporting.venue || {};
        var venueLines = (venue.lines || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        var partners = reporting.partners || [];
        var partnersHtml = partners.length
            ? '<div class="bns-reporting__partners">' +
                partners.map(function (partner) {
                    return '<div class="bns-reporting__partner">' +
                        '<span>' + escapeHtml(partner.label || '') + '</span>' +
                        '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                    '</div>';
                }).join('') +
              '</div>'
            : '';

        var sessions = reporting.sessions || [];
        var detailsHtml = '';
        if (sessions.length) {
            detailsHtml = '<div class="bns-reporting__sessions">' +
                sessions.map(function (session) {
                    var tone = session.tone === 'blue' ? 'blue' : 'green';
                    return '<div class="bns-reporting__session bns-reporting__session--' + tone + '">' +
                        '<strong class="bns-reporting__session-label">' + escapeHtml(session.label || 'Session') + '</strong>' +
                        '<div class="bns-reporting__detail"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(session.date || '') + '</strong></div></div>' +
                        '<div class="bns-reporting__detail bns-reporting__detail--accent"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(session.report_time || '') + '</strong></div></div>' +
                        '<div class="bns-reporting__detail bns-reporting__detail--full"><i class="fas fa-microphone" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(session.seminar_time || '') + '</strong></div></div>' +
                    '</div>';
                }).join('') +
            '</div>';
        } else {
            detailsHtml = '<div class="bns-reporting__details">' +
                '<h4><span>📅 ' + escapeHtml(reporting.details_title || 'Reporting Details') + '</span></h4>' +
                '<div class="bns-reporting__detail-grid">' +
                    '<div class="bns-reporting__detail"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(reporting.date || '') + '</strong></div></div>' +
                    '<div class="bns-reporting__detail bns-reporting__detail--accent"><i class="fas fa-user-clock" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(reporting.report_time || '') + '</strong></div></div>' +
                    '<div class="bns-reporting__detail"><i class="fas fa-microphone" aria-hidden="true"></i><div><span>Seminar Time</span><strong>' + escapeHtml(reporting.seminar_time || '') + '</strong></div></div>' +
                '</div>' +
            '</div>';
        }

        return '<div class="bns-reporting">' +
            '<div class="bns-reporting__hero">' +
                '<span class="bns-reporting__hero-badge">' + escapeHtml(reporting.eyebrow || 'Be On Time') + '</span>' +
                '<div class="bns-reporting__hero-icon" aria-hidden="true"><i class="fas fa-clock"></i></div>' +
                '<h3>⏰ ' + escapeHtml(reporting.headline || 'Reporting Time') + '</h3>' +
                (reporting.greeting ? '<p class="bns-reporting__greeting">' + escapeHtml(reporting.greeting) + '</p>' : '') +
                '<p class="bns-reporting__hero-sub">' + escapeHtml(reporting.intro || '') + '</p>' +
            '</div>' +

            detailsHtml +

            '<div class="bns-reporting__card">' +
                '<h4><span>📌 ' + escapeHtml(reporting.why_title || 'Why Report Early?') + '</span></h4>' +
                (reporting.why_intro ? '<p class="bns-reporting__card-intro">' + escapeHtml(reporting.why_intro) + '</p>' : '') +
                (why ? '<ul class="bns-reporting__list">' + why + '</ul>' : '') +
            '</div>' +

            (venue.title || venueLines
                ? '<div class="bns-reporting__card">' +
                    '<h4><span>📍 Venue</span></h4>' +
                    (venue.title ? '<strong class="bns-reporting__venue-title">' + escapeHtml(venue.title) + '</strong>' : '') +
                    (venueLines ? '<ul class="bns-reporting__venue-lines">' + venueLines + '</ul>' : '') +
                    (venue.maps_url
                        ? '<a class="bns-reporting__maps" href="' + escapeAttr(venue.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS Location' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            partnersHtml +

            '<div class="bns-reporting__note">' +
                '<h4><span>📍 ' + escapeHtml(reporting.note_title || 'Important Note') + '</span></h4>' +
                (reporting.travel
                    ? '<div class="bns-reporting__note-row"><span aria-hidden="true">🚗</span><p>' + escapeHtml(reporting.travel) + '</p></div>'
                    : '') +
                (reporting.seats
                    ? '<div class="bns-reporting__seats"><span aria-hidden="true">💺</span><strong>' + escapeHtml(reporting.seats) + '</strong></div>'
                    : '') +
                (reporting.seats_note ? '<p class="bns-reporting__seats-note">' + escapeHtml(reporting.seats_note) + '</p>' : '') +
            '</div>' +

            '<div class="bns-reporting__footer">' +
                (reporting.thanks ? '<strong class="bns-reporting__thanks">' + escapeHtml(reporting.thanks) + '</strong>' : '') +
                (reporting.closing ? '<p>' + escapeHtml(reporting.closing) + '</p>' : '') +
                (reporting.brand ? '<strong class="bns-reporting__brand">' + escapeHtml(reporting.brand) + '</strong>' : '') +
                (motto ? '<div class="bns-reporting__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderBizcard(bizcard) {
        if (!bizcard) {
            return '';
        }

        var why = (bizcard.why || []).map(function (text) {
            return '<li class="bns-bizcard__item">' +
                '<span class="bns-bizcard__check" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var fields = (bizcard.fields || []).map(function (text, index) {
            return '<li class="bns-bizcard__field">' +
                '<span class="bns-bizcard__field-num" aria-hidden="true">' + String(index + 1).padStart(2, '0') + '</span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var motto = (bizcard.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        return '<div class="bns-bizcard">' +
            '<div class="bns-bizcard__hero">' +
                '<span class="bns-bizcard__hero-badge">' + escapeHtml(bizcard.eyebrow || 'Networking Tip') + '</span>' +
                '<div class="bns-bizcard__hero-icon" aria-hidden="true"><i class="fas fa-id-card"></i></div>' +
                '<h3>💼 ' + escapeHtml(bizcard.headline || 'Business Card Reminder') + '</h3>' +
                (bizcard.greeting ? '<p class="bns-bizcard__greeting">' + escapeHtml(bizcard.greeting) + '</p>' : '') +
                '<p class="bns-bizcard__hero-sub">' + escapeHtml(bizcard.intro || '') + '</p>' +
            '</div>' +

            '<div class="bns-bizcard__card">' +
                '<h4><span>🤝 ' + escapeHtml(bizcard.why_title || 'Why Bring Your Business Card?') + '</span></h4>' +
                (bizcard.why_intro ? '<p class="bns-bizcard__card-intro">' + escapeHtml(bizcard.why_intro) + '</p>' : '') +
                (why ? '<ul class="bns-bizcard__list">' + why + '</ul>' : '') +
            '</div>' +

            '<div class="bns-bizcard__alt">' +
                '<h4><span>📌 ' + escapeHtml(bizcard.alt_title || 'If You Don\'t Have a Business Card') + '</span></h4>' +
                (bizcard.alt_badge ? '<span class="bns-bizcard__alt-badge">' + escapeHtml(bizcard.alt_badge) + '</span>' : '') +
                (bizcard.alt_welcome ? '<p class="bns-bizcard__alt-welcome">' + escapeHtml(bizcard.alt_welcome) + '</p>' : '') +
                (bizcard.alt_hint ? '<p class="bns-bizcard__alt-hint">' + escapeHtml(bizcard.alt_hint) + '</p>' : '') +
                (fields ? '<ul class="bns-bizcard__fields">' + fields + '</ul>' : '') +
            '</div>' +

            '<div class="bns-bizcard__remember">' +
                '<span class="bns-bizcard__remember-label">' + escapeHtml(bizcard.remember_title || 'Remember') + '</span>' +
                (bizcard.remember_quote ? '<strong class="bns-bizcard__quote">' + escapeHtml(bizcard.remember_quote) + '</strong>' : '') +
                (bizcard.remember_text ? '<p>' + escapeHtml(bizcard.remember_text) + '</p>' : '') +
            '</div>' +

            '<div class="bns-bizcard__footer">' +
                (bizcard.closing ? '<p>' + escapeHtml(bizcard.closing) + '</p>' : '') +
                (bizcard.brand ? '<strong class="bns-bizcard__brand">' + escapeHtml(bizcard.brand) + '</strong>' : '') +
                (motto ? '<div class="bns-bizcard__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderDress(dress) {
        if (!dress) {
            return '';
        }

        var recommended = (dress.recommended || []).map(function (text) {
            return '<li class="bns-dress__item bns-dress__item--yes">' +
                '<span class="bns-dress__mark" aria-hidden="true"><i class="fas fa-check"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var avoid = (dress.avoid || []).map(function (text) {
            return '<li class="bns-dress__item bns-dress__item--no">' +
                '<span class="bns-dress__mark" aria-hidden="true"><i class="fas fa-times"></i></span>' +
                '<strong>' + escapeHtml(text) + '</strong>' +
                '</li>';
        }).join('');

        var why = (dress.why || []).map(function (text) {
            return '<li class="bns-dress__why-item">' +
                '<span class="bns-dress__why-dot" aria-hidden="true"></span>' +
                '<span>' + escapeHtml(text) + '</span>' +
                '</li>';
        }).join('');

        var motto = (dress.motto || []).map(function (item) {
            return '<div class="bns-dress__motto-item">' +
                '<span aria-hidden="true">' + escapeHtml(item.icon || '✨') + '</span>' +
                '<strong>' + escapeHtml(item.text || '') + '</strong>' +
                '</div>';
        }).join('');

        return '<div class="bns-dress">' +
            '<div class="bns-dress__hero">' +
                '<span class="bns-dress__hero-badge">' + escapeHtml(dress.eyebrow || 'Seminar Guidelines') + '</span>' +
                '<div class="bns-dress__hero-icon" aria-hidden="true"><i class="fas fa-user-tie"></i></div>' +
                '<h3>👔 ' + escapeHtml(dress.headline || 'Dress Code') + '</h3>' +
                (dress.greeting ? '<p class="bns-dress__greeting">' + escapeHtml(dress.greeting) + '</p>' : '') +
                '<p class="bns-dress__hero-sub">' + escapeHtml(dress.intro || '') + '</p>' +
            '</div>' +

            '<div class="bns-dress__grid">' +
                '<div class="bns-dress__card bns-dress__card--yes">' +
                    '<h4><span>✅ ' + escapeHtml(dress.recommended_title || 'Recommended Dress Code') + '</span></h4>' +
                    (recommended ? '<ul class="bns-dress__list">' + recommended + '</ul>' : '') +
                '</div>' +
                '<div class="bns-dress__card bns-dress__card--no">' +
                    '<h4><span>🚫 ' + escapeHtml(dress.avoid_title || 'Kindly Avoid') + '</span></h4>' +
                    (avoid ? '<ul class="bns-dress__list">' + avoid + '</ul>' : '') +
                '</div>' +
            '</div>' +

            '<div class="bns-dress__why">' +
                '<h4><span>💼 ' + escapeHtml(dress.why_title || 'Why Dress Professionally?') + '</span></h4>' +
                (dress.why_intro ? '<p>' + escapeHtml(dress.why_intro) + '</p>' : '') +
                (why ? '<ul class="bns-dress__why-list">' + why + '</ul>' : '') +
            '</div>' +

            '<div class="bns-dress__footer">' +
                (dress.thanks ? '<strong class="bns-dress__thanks">' + escapeHtml(dress.thanks) + '</strong>' : '') +
                (dress.closing ? '<p>' + escapeHtml(dress.closing) + '</p>' : '') +
                (motto ? '<div class="bns-dress__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function renderVenue(venue) {
        if (!venue) {
            return '';
        }

        var address = venue.address || {};
        var addressLines = (address.lines || []).map(function (line) {
            return '<li>' + escapeHtml(line) + '</li>';
        }).join('');

        var partners = (venue.partners || []).map(function (partner) {
            return '<div class="bns-venue-msg__partner">' +
                '<span>' + escapeHtml(partner.label || 'Partner') + '</span>' +
                '<strong>' + escapeHtml(partner.name || '') + '</strong>' +
                '</div>';
        }).join('');

        var motto = (venue.motto || []).map(function (text) {
            return '<span>' + escapeHtml(text) + '</span>';
        }).join('');

        var sessions = venue.sessions || [];
        var sessionsHtml = '';
        if (sessions.length) {
            sessionsHtml = '<div class="bns-venue-msg__sessions">' +
                sessions.map(function (session, index) {
                    var tone = session.tone === 'blue' ? 'blue' : 'green';
                    var orDivider = session.or || (index > 0)
                        ? '<div class="bns-venue-msg__or"><span>OR</span></div>'
                        : '';
                    return orDivider +
                        '<div class="bns-venue-msg__session bns-venue-msg__session--' + tone + '">' +
                            '<strong class="bns-venue-msg__session-label">' + escapeHtml(session.label || 'Session') + '</strong>' +
                            '<div class="bns-venue-msg__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(session.date || '') + '</strong></div></div>' +
                            '<div class="bns-venue-msg__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Time</span><strong>' + escapeHtml(session.time || '') + '</strong></div></div>' +
                            (session.reporting
                                ? '<div class="bns-venue-msg__event-row bns-venue-msg__event-row--full"><i class="fas fa-hourglass-start" aria-hidden="true"></i><div><span>Reporting Time</span><strong>' + escapeHtml(session.reporting) + '</strong></div></div>'
                                : '') +
                        '</div>';
                }).join('') +
            '</div>';
        } else {
            sessionsHtml = '<div class="bns-venue-msg__event">' +
                '<div class="bns-venue-msg__event-row"><i class="fas fa-calendar-alt" aria-hidden="true"></i><div><span>Date</span><strong>' + escapeHtml(venue.date || '') + '</strong></div></div>' +
                '<div class="bns-venue-msg__event-row"><i class="fas fa-clock" aria-hidden="true"></i><div><span>Time</span><strong>' + escapeHtml(venue.time || '') + '</strong></div></div>' +
            '</div>';
        }

        return '<div class="bns-venue-msg">' +
            '<div class="bns-venue-msg__hero">' +
                '<span class="bns-venue-msg__hero-badge">' + escapeHtml(venue.eyebrow || 'Event Location') + '</span>' +
                '<h3>📍 ' + escapeHtml(venue.headline || 'Venue, Date, Time & Location') + '</h3>' +
                '<p class="bns-venue-msg__hero-sub">' + escapeHtml(venue.intro || '') + '</p>' +
            '</div>' +

            sessionsHtml +

            (address.title || addressLines
                ? '<div class="bns-venue-msg__card">' +
                    '<h4 class="bns-venue-msg__card-title"><span>📍 Venue</span></h4>' +
                    (address.title ? '<strong class="bns-venue-msg__address-title">' + escapeHtml(address.title) + '</strong>' : '') +
                    (addressLines ? '<ul class="bns-venue-msg__address-lines">' + addressLines + '</ul>' : '') +
                    (address.maps_url
                        ? '<a class="bns-venue-msg__maps" href="' + escapeAttr(address.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                            '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS Location' +
                          '</a>'
                        : '') +
                  '</div>'
                : '') +

            (partners ? '<div class="bns-venue-msg__partners">' + partners + '</div>' : '') +

            (venue.badge ? '<div class="bns-venue-msg__badge">' + escapeHtml(venue.badge) + '</div>' : '') +

            '<div class="bns-venue-msg__actions">' +
                (venue.register_url
                    ? '<a class="bns-venue-msg__action bns-venue-msg__action--register" href="' + escapeAttr(venue.register_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-clipboard-list" aria-hidden="true"></i> ' + escapeHtml(venue.register_label || 'Register Today') +
                      '</a>'
                    : '') +
                (address.maps_url
                    ? '<a class="bns-venue-msg__action bns-venue-msg__action--maps" href="' + escapeAttr(address.maps_url) + '" target="_blank" rel="noopener noreferrer">' +
                        '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Open GPS' +
                      '</a>'
                    : '') +
            '</div>' +

            '<div class="bns-venue-msg__footer">' +
                (venue.closing ? '<p>' + escapeHtml(venue.closing) + '</p>' : '') +
                (venue.brand ? '<strong>' + escapeHtml(venue.brand) + '</strong>' : '') +
                (motto ? '<div class="bns-venue-msg__motto">' + motto + '</div>' : '') +
            '</div>' +
        '</div>';
    }

    function boot() {
        var catalogNode = document.getElementById('bnsMessageCatalog');
        var modalEl = document.getElementById('bnsMessageViewerModal');
        var mailModalEl = document.getElementById('bnsMessageMailModal');
        if (!catalogNode || !modalEl || !window.bootstrap || !bootstrap.Modal) {
            return;
        }

        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }
        if (mailModalEl && mailModalEl.parentElement !== document.body) {
            document.body.appendChild(mailModalEl);
        }

        var catalog = [];
        try {
            catalog = JSON.parse(catalogNode.textContent || '[]');
        } catch (e) {
            catalog = [];
        }

        var state = { section: null, index: 0 };
        var modal = getModalInstance(modalEl);
        var mailModal = mailModalEl ? getModalInstance(mailModalEl) : null;
        var dialog = modalEl.querySelector('.modal-dialog');
        var mailForm = document.getElementById('bnsMessageMailForm');
        var mailUi = {
            title: mailModalEl ? mailModalEl.querySelector('[data-mail-title]') : null,
            email: mailModalEl ? mailModalEl.querySelector('[data-mail-email]') : null,
            error: mailModalEl ? mailModalEl.querySelector('[data-mail-error]') : null,
            success: mailModalEl ? mailModalEl.querySelector('[data-mail-success]') : null,
            submit: mailModalEl ? mailModalEl.querySelector('[data-mail-submit]') : null,
            submitLabel: mailModalEl ? mailModalEl.querySelector('[data-mail-submit-label]') : null
        };

        var ui = {
            section: modalEl.querySelector('[data-message-section]'),
            title: modalEl.querySelector('[data-message-title]'),
            counter: modalEl.querySelector('[data-message-counter]'),
            body: modalEl.querySelector('[data-message-body]'),
            links: modalEl.querySelector('[data-message-links]'),
            sheet: modalEl.querySelector('.bns-message-modal__sheet'),
            imageWrap: modalEl.querySelector('[data-message-image-wrap]'),
            image: modalEl.querySelector('[data-message-image]'),
            prev: modalEl.querySelector('[data-message-nav="prev"]'),
            next: modalEl.querySelector('[data-message-nav="next"]'),
            copy: modalEl.querySelector('[data-message-copy]'),
            send: modalEl.querySelector('[data-message-send]'),
            mail: modalEl.querySelector('[data-message-mail]'),
            cta: modalEl.querySelector('[data-message-cta]'),
            ctaLabel: modalEl.querySelector('[data-message-cta-label]')
        };

        function sectionItems(section) {
            return catalog.filter(function (item) {
                return item.section === section;
            });
        }

        function currentItem() {
            return sectionItems(state.section)[state.index] || null;
        }

        function renderMessage() {
            var item = currentItem();
            if (!item) {
                return false;
            }

            var items = sectionItems(state.section);
            var isPromo = item.layout === 'promo' && item.promo;
            var isAbout = item.layout === 'about' && item.about;
            var isVision = item.layout === 'vision' && item.vision;
            var isPitch = item.layout === 'pitch' && item.pitch;
            var isReels = item.layout === 'reels' && item.reels;
            var isJourney = item.layout === 'journey' && item.journey;
            var isBenefits = item.layout === 'benefits' && item.benefits;
            var isHighlights = item.layout === 'highlights' && item.highlights;
            var isBring = item.layout === 'bring' && item.bring;
            var isCountdown = item.layout === 'countdown' && item.countdown;
            var isConfirm = item.layout === 'confirm' && item.confirm;
            var isThanks = item.layout === 'thanks' && item.thanks;
            var isSaveDate = item.layout === 'savedate' && item.savedate;
            var isCalReminder = item.layout === 'calreminder' && item.calreminder;
            var isWaChannel = item.layout === 'wachannel' && item.wachannel;
            var isVenue = item.layout === 'venue' && item.venue;
            var isDress = item.layout === 'dress' && item.dress;
            var isBizcard = item.layout === 'bizcard' && item.bizcard;
            var isReporting = item.layout === 'reporting' && item.reporting;
            var isSurprise = item.layout === 'surprise' && item.surprise;
            var isTomorrow = item.layout === 'tomorrow' && item.tomorrow;
            var isChecklist = item.layout === 'checklist' && item.checklist;
            var isFounder = item.layout === 'founder' && item.founder;
            var isToday = item.layout === 'today' && item.today;
            var isReminder = item.layout === 'reminder' && item.reminder;
            var isWelcome = item.layout === 'welcome' && item.welcome;
            var isVenueGps = item.layout === 'venuegps' && item.venuegps;
            var isWelcomeReg = item.layout === 'welcomereg' && item.welcomereg;
            var isAttendance = item.layout === 'attendance' && item.attendance;
            var isInstructions = item.layout === 'instructions' && item.instructions;
            var isAdmitCounter = item.layout === 'admitcounter' && item.admitcounter;
            var isScholarship = item.layout === 'scholarship' && item.scholarship;
            var isUsefulLinks = item.layout === 'usefullinks' && item.usefullinks;
            var isSemThanks = item.layout === 'semthanks' && item.semthanks;
            var isPhotoGallery = item.layout === 'photogallery' && item.photogallery;
            var isIntroSession = item.layout === 'introsession' && item.introsession;
            var isSyllabus = item.layout === 'syllabus' && item.syllabus;
            var isAdmitReminder = item.layout === 'admitreminder' && item.admitreminder;
            var isPayNow = item.layout === 'paynow' && item.paynow;
            var isFirstBatch = item.layout === 'firstbatch' && item.firstbatch;
            var isFaq = item.layout === 'faq' && item.faq;
            var isBnsFamily = item.layout === 'bnsfamily' && item.bnsfamily;
            var isFounderWelcome = item.layout === 'founderwelcome' && item.founderwelcome;
            var isCoach = item.layout === 'coach' && item.coach;
            var isRich = isPromo || isAbout || isVision || isPitch || isReels || isJourney || isBenefits || isHighlights || isBring || isCountdown || isConfirm || isThanks || isSaveDate || isCalReminder || isWaChannel || isVenue || isDress || isBizcard || isReporting || isSurprise || isTomorrow || isChecklist || isFounder || isToday || isReminder || isWelcome || isVenueGps || isWelcomeReg || isAttendance || isInstructions || isAdmitCounter || isScholarship || isUsefulLinks || isSemThanks || isPhotoGallery || isIntroSession || isSyllabus || isAdmitReminder || isPayNow || isFirstBatch || isFaq || isBnsFamily || isFounderWelcome || isCoach;

            if (dialog) {
                dialog.classList.toggle('modal-xl', !!isRich);
                dialog.classList.toggle('modal-lg', !isRich);
            }
            if (ui.sheet) {
                ui.sheet.classList.toggle('bns-message-modal__sheet--promo', !!isPromo);
                ui.sheet.classList.toggle('bns-message-modal__sheet--about', !!isAbout);
                ui.sheet.classList.toggle('bns-message-modal__sheet--vision', !!isVision);
                ui.sheet.classList.toggle('bns-message-modal__sheet--pitch', !!isPitch);
                ui.sheet.classList.toggle('bns-message-modal__sheet--reels', !!isReels);
                ui.sheet.classList.toggle('bns-message-modal__sheet--journey', !!isJourney);
                ui.sheet.classList.toggle('bns-message-modal__sheet--benefits', !!isBenefits);
                ui.sheet.classList.toggle('bns-message-modal__sheet--highlights', !!isHighlights);
                ui.sheet.classList.toggle('bns-message-modal__sheet--bring', !!isBring);
                ui.sheet.classList.toggle('bns-message-modal__sheet--countdown', !!isCountdown);
                ui.sheet.classList.toggle('bns-message-modal__sheet--confirm', !!isConfirm);
                ui.sheet.classList.toggle('bns-message-modal__sheet--thanks', !!isThanks);
                ui.sheet.classList.toggle('bns-message-modal__sheet--savedate', !!isSaveDate);
                ui.sheet.classList.toggle('bns-message-modal__sheet--calreminder', !!isCalReminder);
                ui.sheet.classList.toggle('bns-message-modal__sheet--wachannel', !!isWaChannel);
                ui.sheet.classList.toggle('bns-message-modal__sheet--venue', !!isVenue);
                ui.sheet.classList.toggle('bns-message-modal__sheet--dress', !!isDress);
                ui.sheet.classList.toggle('bns-message-modal__sheet--bizcard', !!isBizcard);
                ui.sheet.classList.toggle('bns-message-modal__sheet--reporting', !!isReporting);
                ui.sheet.classList.toggle('bns-message-modal__sheet--surprise', !!isSurprise);
                ui.sheet.classList.toggle('bns-message-modal__sheet--tomorrow', !!isTomorrow);
                ui.sheet.classList.toggle('bns-message-modal__sheet--checklist', !!isChecklist);
                ui.sheet.classList.toggle('bns-message-modal__sheet--founder', !!isFounder);
                ui.sheet.classList.toggle('bns-message-modal__sheet--today', !!isToday);
                ui.sheet.classList.toggle('bns-message-modal__sheet--reminder', !!isReminder);
                ui.sheet.classList.toggle('bns-message-modal__sheet--welcome', !!isWelcome);
                ui.sheet.classList.toggle('bns-message-modal__sheet--venuegps', !!isVenueGps);
                ui.sheet.classList.toggle('bns-message-modal__sheet--welcomereg', !!isWelcomeReg);
                ui.sheet.classList.toggle('bns-message-modal__sheet--attendance', !!isAttendance);
                ui.sheet.classList.toggle('bns-message-modal__sheet--instructions', !!isInstructions);
                ui.sheet.classList.toggle('bns-message-modal__sheet--admitcounter', !!isAdmitCounter);
                ui.sheet.classList.toggle('bns-message-modal__sheet--scholarship', !!isScholarship);
                ui.sheet.classList.toggle('bns-message-modal__sheet--usefullinks', !!isUsefulLinks);
                ui.sheet.classList.toggle('bns-message-modal__sheet--semthanks', !!isSemThanks);
                ui.sheet.classList.toggle('bns-message-modal__sheet--photogallery', !!isPhotoGallery);
                ui.sheet.classList.toggle('bns-message-modal__sheet--introsession', !!isIntroSession);
                ui.sheet.classList.toggle('bns-message-modal__sheet--syllabus', !!isSyllabus);
                ui.sheet.classList.toggle('bns-message-modal__sheet--admitreminder', !!isAdmitReminder);
                ui.sheet.classList.toggle('bns-message-modal__sheet--paynow', !!isPayNow);
                ui.sheet.classList.toggle('bns-message-modal__sheet--firstbatch', !!isFirstBatch);
                ui.sheet.classList.toggle('bns-message-modal__sheet--faq', !!isFaq);
                ui.sheet.classList.toggle('bns-message-modal__sheet--bnsfamily', !!isBnsFamily);
                ui.sheet.classList.toggle('bns-message-modal__sheet--founderwelcome', !!isFounderWelcome);
                ui.sheet.classList.toggle('bns-message-modal__sheet--coach', !!isCoach);
            }

            if (ui.section) {
                ui.section.textContent = item.section_title || 'Message';
            }
            if (ui.title) {
                ui.title.textContent = item.title || 'Message';
            }
            if (ui.counter) {
                ui.counter.textContent = (state.index + 1) + ' / ' + items.length;
            }
            if (ui.prev) {
                ui.prev.disabled = state.index <= 0;
            }
            if (ui.next) {
                ui.next.disabled = state.index >= items.length - 1;
            }

            if (ui.body) {
                if (isPromo) {
                    ui.body.innerHTML = renderPromo(item.promo);
                } else if (isAbout) {
                    ui.body.innerHTML = renderAbout(item.about);
                } else if (isVision) {
                    ui.body.innerHTML = renderVision(item.vision);
                } else if (isPitch) {
                    ui.body.innerHTML = renderPitch(item.pitch);
                } else if (isReels) {
                    ui.body.innerHTML = renderReels(item.reels);
                } else if (isJourney) {
                    ui.body.innerHTML = renderJourney(item.journey);
                } else if (isBenefits) {
                    ui.body.innerHTML = renderBenefits(item.benefits);
                } else if (isHighlights) {
                    ui.body.innerHTML = renderHighlights(item.highlights);
                } else if (isBring) {
                    ui.body.innerHTML = renderBring(item.bring);
                } else if (isCountdown) {
                    ui.body.innerHTML = renderCountdown(item.countdown);
                } else if (isConfirm) {
                    ui.body.innerHTML = renderConfirm(item.confirm);
                } else if (isThanks) {
                    ui.body.innerHTML = renderThanks(item.thanks);
                } else if (isSaveDate) {
                    ui.body.innerHTML = renderSaveDate(item.savedate);
                } else if (isCalReminder) {
                    ui.body.innerHTML = renderCalReminder(item.calreminder);
                } else if (isWaChannel) {
                    ui.body.innerHTML = renderWaChannel(item.wachannel);
                } else if (isVenue) {
                    ui.body.innerHTML = renderVenue(item.venue);
                } else if (isDress) {
                    ui.body.innerHTML = renderDress(item.dress);
                } else if (isBizcard) {
                    ui.body.innerHTML = renderBizcard(item.bizcard);
                } else if (isReporting) {
                    ui.body.innerHTML = renderReporting(item.reporting);
                } else if (isSurprise) {
                    ui.body.innerHTML = renderSurprise(item.surprise);
                } else if (isTomorrow) {
                    ui.body.innerHTML = renderTomorrow(item.tomorrow);
                } else if (isChecklist) {
                    ui.body.innerHTML = renderChecklist(item.checklist);
                } else if (isFounder) {
                    ui.body.innerHTML = renderFounder(item.founder);
                } else if (isToday) {
                    ui.body.innerHTML = renderToday(item.today);
                } else if (isReminder) {
                    ui.body.innerHTML = renderReminder(item.reminder);
                } else if (isWelcome) {
                    ui.body.innerHTML = renderWelcome(item.welcome);
                } else if (isVenueGps) {
                    ui.body.innerHTML = renderVenueGps(item.venuegps);
                } else if (isWelcomeReg) {
                    ui.body.innerHTML = renderWelcomeReg(item.welcomereg);
                } else if (isAttendance) {
                    ui.body.innerHTML = renderAttendance(item.attendance);
                } else if (isInstructions) {
                    ui.body.innerHTML = renderInstructions(item.instructions);
                } else if (isAdmitCounter) {
                    ui.body.innerHTML = renderAdmitCounter(item.admitcounter);
                } else if (isScholarship) {
                    ui.body.innerHTML = renderScholarship(item.scholarship);
                } else if (isUsefulLinks) {
                    ui.body.innerHTML = renderUsefulLinks(item.usefullinks);
                } else if (isSemThanks) {
                    ui.body.innerHTML = renderSemThanks(item.semthanks);
                } else if (isPhotoGallery) {
                    ui.body.innerHTML = renderPhotoGallery(item.photogallery);
                } else if (isIntroSession) {
                    ui.body.innerHTML = renderIntroSession(item.introsession);
                } else if (isSyllabus) {
                    ui.body.innerHTML = renderSyllabus(item.syllabus);
                } else if (isAdmitReminder) {
                    ui.body.innerHTML = renderAdmitReminder(item.admitreminder);
                } else if (isPayNow) {
                    ui.body.innerHTML = renderPayNow(item.paynow);
                } else if (isFirstBatch) {
                    ui.body.innerHTML = renderFirstBatch(item.firstbatch);
                } else if (isFaq) {
                    ui.body.innerHTML = renderFaq(item.faq);
                } else if (isBnsFamily) {
                    ui.body.innerHTML = renderBnsFamily(item.bnsfamily);
                } else if (isFounderWelcome) {
                    ui.body.innerHTML = renderFounderWelcome(item.founderwelcome);
                } else if (isCoach) {
                    ui.body.innerHTML = renderCoach(item.coach);
                } else if (item.plain) {
                    ui.body.innerHTML = '<div class="bns-message-modal__plain">' + escapeHtml(item.plain).replace(/\n/g, '<br>') + '</div>';
                } else {
                    ui.body.innerHTML = (item.body || []).map(function (html) {
                        return '<p>' + html + '</p>';
                    }).join('');
                }
            }

            if (ui.imageWrap && ui.image) {
                if (item.image) {
                    ui.image.src = item.image;
                    ui.image.alt = item.title || 'Message';
                    ui.imageWrap.classList.remove('d-none');
                } else {
                    ui.image.removeAttribute('src');
                    ui.imageWrap.classList.add('d-none');
                }
            }

            if (ui.links) {
                var links = item.links || [];
                if (!isRich && links.length) {
                    ui.links.innerHTML = links.map(function (link) {
                        var external = link.external
                            ? ' target="_blank" rel="noopener noreferrer"'
                            : '';
                        return '<a href="' + escapeAttr(link.url) + '" class="bns-message-modal__link"' + external + '>' +
                            '<i class="fas fa-link" aria-hidden="true"></i> ' + escapeHtml(link.label) +
                            '</a>';
                    }).join('');
                    ui.links.classList.remove('d-none');
                } else {
                    ui.links.innerHTML = '';
                    ui.links.classList.add('d-none');
                }
            }

            if (ui.send) {
                ui.send.href = 'https://wa.me/?text=' + encodeURIComponent(item.plain || '');
            }

            if (ui.cta && ui.ctaLabel) {
                if (!isRich && item.cta && item.cta.url) {
                    ui.cta.href = item.cta.url;
                    ui.ctaLabel.textContent = item.cta.label || 'Continue';
                    ui.cta.classList.remove('d-none');
                } else {
                    ui.cta.classList.add('d-none');
                    ui.cta.removeAttribute('href');
                }
            }

            var scrollBody = modalEl.querySelector('.modal-body');
            if (scrollBody) {
                scrollBody.scrollTop = 0;
            }

            return true;
        }

        function setMessage(section, index) {
            state.section = section;
            state.index = Math.max(0, parseInt(index, 10) || 0);
            return renderMessage();
        }

        function move(delta) {
            var items = sectionItems(state.section);
            var nextIndex = state.index + delta;
            if (nextIndex < 0 || nextIndex >= items.length) {
                return;
            }
            state.index = nextIndex;
            renderMessage();
        }

        function openFromTrigger(trigger) {
            if (!trigger) {
                return;
            }
            setMessage(
                trigger.getAttribute('data-section'),
                trigger.getAttribute('data-index')
            );
        }

        modalEl.addEventListener('show.bs.modal', function (event) {
            openFromTrigger(event.relatedTarget);
        });

        document.addEventListener('click', function (event) {
            var openBtn = event.target.closest('[data-message-open]');
            if (openBtn) {
                openFromTrigger(openBtn);
                if (!modalEl.classList.contains('show') && modal) {
                    modal.show();
                }
                return;
            }

            var navBtn = event.target.closest('[data-message-nav]');
            if (navBtn && modalEl.contains(navBtn)) {
                event.preventDefault();
                event.stopPropagation();
                move(navBtn.getAttribute('data-message-nav') === 'prev' ? -1 : 1);
                return;
            }

            var mailBtn = event.target.closest('[data-message-mail]');
            if (mailBtn && modalEl.contains(mailBtn)) {
                event.preventDefault();
                openMailModal();
                return;
            }

            var copyBtn = event.target.closest('[data-message-copy]');
            if (!copyBtn || !modalEl.contains(copyBtn)) {
                return;
            }

            event.preventDefault();
            var item = currentItem();
            var text = item && item.plain ? item.plain : '';
            if (!text) {
                return;
            }

            var done = function () {
                var original = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="fas fa-check" aria-hidden="true"></i> Copied';
                setTimeout(function () {
                    copyBtn.innerHTML = original;
                }, 1600);
            };

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(done).catch(function () {});
                return;
            }

            var area = document.createElement('textarea');
            area.value = text;
            document.body.appendChild(area);
            area.select();
            try {
                document.execCommand('copy');
                done();
            } catch (e) {}
            document.body.removeChild(area);
        });

        function csrfToken() {
            var meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        }

        function setMailFeedback(type, message) {
            if (mailUi.error) {
                mailUi.error.textContent = type === 'error' ? (message || '') : '';
                mailUi.error.classList.toggle('d-none', type !== 'error' || !message);
            }
            if (mailUi.success) {
                mailUi.success.textContent = type === 'success' ? (message || '') : '';
                mailUi.success.classList.toggle('d-none', type !== 'success' || !message);
            }
        }

        function setMailSubmitting(isSubmitting) {
            if (!mailUi.submit) {
                return;
            }
            mailUi.submit.disabled = !!isSubmitting;
            if (mailUi.submitLabel) {
                mailUi.submitLabel.textContent = isSubmitting ? 'Sending...' : 'Send Mail';
            }
        }

        function openMailModal() {
            var item = currentItem();
            if (!item || !mailModal) {
                return;
            }

            if (mailUi.title) {
                mailUi.title.textContent = item.title || 'Send Mail';
            }
            if (mailForm) {
                mailForm.reset();
            }
            if (mailUi.email) {
                mailUi.email.value = '';
            }
            setMailFeedback('', '');
            setMailSubmitting(false);
            mailModal.show();
            setTimeout(function () {
                if (mailUi.email) {
                    mailUi.email.focus();
                }
            }, 250);
        }

        if (mailModalEl) {
            mailModalEl.addEventListener('shown.bs.modal', function () {
                var backdrops = document.querySelectorAll('.modal-backdrop');
                var last = backdrops[backdrops.length - 1];
                if (last) {
                    last.classList.add('bns-message-mail-backdrop');
                }
                mailModalEl.style.zIndex = '100070';
            });

            mailModalEl.addEventListener('hidden.bs.modal', function () {
                document.querySelectorAll('.modal-backdrop.bns-message-mail-backdrop').forEach(function (el) {
                    el.classList.remove('bns-message-mail-backdrop');
                });
                // Keep message modal usable after nested mail modal closes
                if (modalEl.classList.contains('show')) {
                    document.body.classList.add('modal-open');
                }
            });
        }

        if (mailForm) {
            mailForm.addEventListener('submit', function (event) {
                event.preventDefault();

                var item = currentItem();
                var email = mailUi.email ? String(mailUi.email.value || '').trim() : '';
                var sendUrl = window.bnsMessageMailSendUrl || '/message/send-mail';

                if (!item || !item.id) {
                    setMailFeedback('error', 'Message template is missing.');
                    return;
                }
                if (!email) {
                    setMailFeedback('error', 'Please enter an email address.');
                    if (mailUi.email) {
                        mailUi.email.focus();
                    }
                    return;
                }

                setMailFeedback('', '');
                setMailSubmitting(true);

                fetch(sendUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken(),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        email: email,
                        template: item.id
                    })
                })
                    .then(function (response) {
                        return response.json().then(function (data) {
                            return { ok: response.ok, status: response.status, data: data || {} };
                        }).catch(function () {
                            return { ok: false, status: response.status, data: {} };
                        });
                    })
                    .then(function (result) {
                        setMailSubmitting(false);

                        if (result.ok && result.data.ok) {
                            setMailFeedback('success', result.data.message || 'Mail sent successfully.');
                            if (mailUi.email) {
                                mailUi.email.value = '';
                            }
                            return;
                        }

                        var message = result.data.message
                            || (result.data.errors && result.data.errors.email && result.data.errors.email[0])
                            || (result.data.errors && result.data.errors.template && result.data.errors.template[0])
                            || 'Unable to send mail. Please try again.';
                        if (typeof message !== 'string') {
                            message = 'Unable to send mail. Please try again.';
                        }
                        setMailFeedback('error', message);
                    })
                    .catch(function () {
                        setMailSubmitting(false);
                        setMailFeedback('error', 'Unable to send mail. Please try again.');
                    });
            });
        }

        if (ui.send) {
            ui.send.addEventListener('click', function () {
                var item = currentItem();
                if (!item) {
                    return;
                }
                ui.send.href = 'https://wa.me/?text=' + encodeURIComponent(item.plain || '');
            });
        }

        document.addEventListener('keydown', function (event) {
            if (!modalEl.classList.contains('show')) {
                return;
            }
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                move(-1);
            }
            if (event.key === 'ArrowRight') {
                event.preventDefault();
                move(1);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
