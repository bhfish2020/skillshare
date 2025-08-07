<?php

namespace Database\Seeders;

use App\Models\SkillCategory;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        // Create Programming category
        $programming = SkillCategory::create([
            'name' => 'Programming',
            'description' => 'Programming languages, frameworks, and development tools'
        ]);

        $programmingSkills = [
            'JavaScript', 'TypeScript', 'Python', 'PHP', 'Java', 'C#', 'C++', 'Ruby', 'Go', 'Rust',
            'React', 'Vue.js', 'Angular', 'Laravel', 'Django', 'Node.js', 'Express.js', 'Next.js',
            'Mobile Development', 'iOS Development', 'Android Development', 'Flutter', 'React Native',
            'DevOps', 'AWS', 'Docker', 'Kubernetes', 'Git', 'Database Design', 'SQL', 'MongoDB'
        ];

        foreach ($programmingSkills as $skill) {
            Skill::create([
                'skill_category_id' => $programming->id,
                'name' => $skill,
            ]);
        }

        // Create Design category
        $design = SkillCategory::create([
            'name' => 'Design',
            'description' => 'Visual design, user experience, and creative skills'
        ]);

        $designSkills = [
            'UI/UX Design', 'Web Design', 'Graphic Design', 'Logo Design', 'Brand Identity',
            'Adobe Photoshop', 'Adobe Illustrator', 'Figma', 'Sketch', 'Adobe XD',
            'Typography', 'Color Theory', 'Wireframing', 'Prototyping', 'User Research',
            'Print Design', 'Packaging Design', 'Motion Graphics', 'Video Editing', 'Photography'
        ];

        foreach ($designSkills as $skill) {
            Skill::create([
                'skill_category_id' => $design->id,
                'name' => $skill,
            ]);
        }

        // Create Other category
        $other = SkillCategory::create([
            'name' => 'Other',
            'description' => 'Business, marketing, and other professional skills'
        ]);

        $otherSkills = [
            'Project Management', 'Digital Marketing', 'SEO', 'Content Writing', 'Copywriting',
            'Social Media Marketing', 'Email Marketing', 'Sales', 'Customer Service', 'Leadership',
            'Public Speaking', 'Translation', 'Data Analysis', 'Excel', 'Financial Analysis',
            'Legal Advice', 'Accounting', 'Business Strategy', 'Product Management', 'Consulting'
        ];

        foreach ($otherSkills as $skill) {
            Skill::create([
                'skill_category_id' => $other->id,
                'name' => $skill,
            ]);
        }
    }
}