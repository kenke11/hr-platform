<?php

namespace Database\Factories;

use App\Models\CandidateApplication;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CandidateApplication>
 */
class CandidateApplicationFactory extends Factory
{
    protected $model = CandidateApplication::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileName = Str::random(20) . '.pdf';
        $path = 'cvs/' . $fileName;

        Storage::disk('public')->put(
            $path,
            $this->dummyPdfContent()
        );

        return [
            'company_id' => null,
            'vacancy_id' => null,

            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),

            'position' => $this->faker->jobTitle(),

            'linkedin_url' => $this->faker->optional()->url(),
            'github_url' => $this->faker->optional()->url(),
            'portfolio_url' => $this->faker->optional()->url(),

            'cv_path' => $path,

            'cover_letter' => $this->faker->optional()->paragraph(),

            'status' => $this->faker->randomElement([
                'new',
                'reviewed',
                'shortlisted',
                'rejected',
            ]),
        ];
    }

    protected function dummyPdfContent(): string
    {
        return <<<PDF
            %PDF-1.4
            1 0 obj
            << /Type /Catalog /Pages 2 0 R >>
            endobj
            2 0 obj
            << /Type /Pages /Kids [3 0 R] /Count 1 >>
            endobj
            3 0 obj
            << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792]
               /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>
            endobj
            4 0 obj
            << /Length 44 >>
            stream
            BT
            /F1 12 Tf
            72 720 Td
            (Dummy CV PDF file) Tj
            ET
            endstream
            endobj
            5 0 obj
            << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>
            endobj
            xref
            0 6
            0000000000 65535 f
            0000000010 00000 n
            0000000060 00000 n
            0000000115 00000 n
            0000000210 00000 n
            0000000310 00000 n
            trailer
            << /Root 1 0 R /Size 6 >>
            startxref
            380
            %%EOF
        PDF;
    }
}
