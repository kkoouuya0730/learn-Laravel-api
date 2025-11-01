<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use PHPUnit\Framework\TestCase;
use App\Http\Requests\Rules\PostValidationRules;

class PostValidationRulesTest extends TestCase
{
    #[Test]
    public function baseルールを返す()
    {
        $expected = [
            'title' => ['required', 'max:255'],
            'content' => ['nullable', 'string']
        ];

        $this->assertSame($expected, PostValidationRules::base());
    }

    #[Test]
    public function storeルールを返す()
    {
        $expected = [
            'title' => ['required', 'max:255'],
            'content' => ['nullable', 'string'],
            'tag_ids' => ['array'],
            'tag_ids.*' => ['exists:tags,id'],
        ];

        $this->assertSame($expected, PostValidationRules::store());
    }

    #[Test]
    public function updateルールを返す()
    {
        $expected = [
            'title' => ['required', 'max:255'],
            'content' => ['nullable', 'string']
        ];

        $this->assertSame($expected, PostValidationRules::update());
    }
}
