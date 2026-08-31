<?php

namespace App\Services;

class HomeAudienceJourneyService
{
    private const REGISTER_FORM_HASHES = [
        'youth-school',
        'student-school',
        'women-school',
        'working-women-school',
        'job-professional-school',
        'business-growth-school',
        'job-business-batch',
        'business-job-professional-batch',
    ];

    /** @return array<string, array<string, mixed>> */
  public function buildForCards(array $cards, array $brochure = []): array
  {
      $journeys = [];

      foreach ($cards as $card) {
          $id = $card['register_hash'] ?? null;
          if (! $id) {
              continue;
          }

          $journeys[$id] = $this->buildJourney($card, $brochure);
      }

      return $journeys;
  }

  /** @param array<string, mixed> $card */
  /** @param array<string, mixed> $brochure */
  private function buildJourney(array $card, array $brochure): array
  {
      $slug = (string) ($card['program_slug'] ?? '');
      $program = $this->findProgram($slug);
      $eligibility = $this->findEligibility((string) ($card['eligibility_title'] ?? $program['title'] ?? ''));

      $introSession = config('admission.pages.introduction-session', []);
      $batchTimings = config('admission.pages.batch-timings', []);
      $academicCalendar = config('admission.pages.academic-calendar', []);
      $courseDuration = config('admission.pages.course-duration', []);

      $sessionItems = array_values(array_filter(array_merge(
          $batchTimings['items'] ?? [],
          $academicCalendar['items'] ?? [],
          $courseDuration['items'] ?? [],
          $introSession['items'] ?? [],
      )));

      $registerHash = (string) ($card['register_hash'] ?? '');
      $admissionHash = $this->admissionFormHash($card);
      $registerUrl = route('register');
      if ($admissionHash !== '') {
          $registerUrl .= '#'.$admissionHash;
      }

      return [
          'id' => $registerHash,
          'label' => $card['label'] ?? '',
          'icon' => $card['icon'] ?? '🎓',
          'desc' => $card['desc'] ?? '',
          'register_url' => $registerUrl,
          'register_program_id' => $admissionHash,
          'program_url' => route('programs.featured') . ($slug ? '#' . $slug : ''),
          'contact_category' => $card['contact_category'] ?? 'Other',
          'contact_program' => $card['contact_program'] ?? ($program['title'] ?? ''),
          'steps' => [
              'details' => [
                  'title' => 'Details',
                  'program_title' => $program['title'] ?? ($card['label'] ?? ''),
                  'audience' => $program['audience'] ?? '',
                  'summary' => $program['summary'] ?? ($card['desc'] ?? ''),
                  'goal' => $program['goal'] ?? '',
                  'duration' => $program['duration'] ?? '',
                  'learn_heading' => $program['learn_heading'] ?? 'What You Will Learn',
                  'learn_preview' => array_slice($program['learn_items'] ?? [], 0, 4),
              ],
              'eligibility' => [
                  'title' => 'Eligibility',
                  'program_title' => $eligibility['title'] ?? ($program['title'] ?? ''),
                  'candidates_label' => $eligibility['candidates_label'] ?? 'Eligible Candidates',
                  'candidates' => $eligibility['candidates'] ?? [],
                  'age_group' => $eligibility['age_group'] ?? null,
                  'general' => config('admission.pages.eligibility-criteria.general_eligibility.items', []),
              ],
              'session' => [
                  'title' => 'Session Information',
                  'intro' => $introSession['intro'] ?? 'Weekly Business School sessions designed for practical learning.',
                  'items' => array_slice($sessionItems, 0, 8),
              ],
              'syllabus' => [
                  'title' => 'Syllabus',
                  'heading' => $program['learn_heading'] ?? 'Program Curriculum',
                  'items' => $program['learn_items'] ?? [],
                  'goal' => $program['goal'] ?? '',
              ],
              'introduction' => [
                  'title' => 'Introduction Session',
                  'intro' => $introSession['intro'] ?? '',
                  'items' => $introSession['items'] ?? [],
              ],
              'admission' => [
                  'title' => 'Admission Form',
                  'intro' => 'Complete the BNS online admission form for your selected program. Registration fee payment is required to confirm your application.',
                  'register_url' => $registerUrl,
              ],
          ],
      ];
  }

  /** @return array<string, mixed> */
  private function findProgram(string $slug): array
  {
      if ($slug === '') {
          return [];
      }

      foreach (config('programs.featured_page.programs', []) as $program) {
          if (($program['slug'] ?? '') === $slug) {
              return $program;
          }
      }

      return [];
  }

  /** @return array<string, mixed> */
  private function findEligibility(string $title): array
  {
      foreach (config('admission.pages.eligibility-criteria.programs', []) as $program) {
          if (($program['title'] ?? '') === $title) {
              return $program;
          }
      }

      return [];
  }

  /** @param array<string, mixed> $card */
  private function admissionFormHash(array $card): string
  {
      $hash = (string) ($card['admission_register_hash'] ?? '');
      if ($hash !== '') {
          return $hash;
      }

      $registerHash = (string) ($card['register_hash'] ?? '');

      return in_array($registerHash, self::REGISTER_FORM_HASHES, true) ? $registerHash : '';
  }
}
