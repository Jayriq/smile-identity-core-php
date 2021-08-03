<?php
declare(strict_types=1);

require 'lib/Signature.php';
use PHPUnit\Framework\TestCase;
use Ouzo\Utilities\Clock;

final class SignatureTest extends TestCase
{
    private sid\Signature $sig;

    protected function setUp(): void
    {
        Clock::freeze('2011-01-02 12:34');
        $partner_id = 212;
        $api_key = file_get_contents(__DIR__ . "/assets/ApiKey.pub");
        $this->sig = new sid\Signature($api_key, $partner_id);
    }

    public function testGenerateSecKey(): void
    {
        $generatedKey = $this->sig->generate_sec_key();
        $timestamp = Clock::now()->getTimestamp();
        $this->assertSame(2, count($generatedKey));
        $this->assertSame($timestamp, $generatedKey[1]);
    }

    public function testGenerateSignature()
    {
        $timestamp = Clock::now()->getTimestamp();
        $signature = $this->sig->generate_signature();
        $this->assertSame(2, count($signature));
        $this->assertSame($timestamp, $signature[1]);
    }

    public function testConfirmSignature()
    {
        $timestamp = Clock::now()->getTimestamp();
        $signature = "oC3tlJLJA9fkYzn5ZJTPbSPKD2yPzVxzO5wTiykjW3w=";
        $confirm_signature = $this->sig->confirm_signature($timestamp, $signature);
        $this->assertTrue($confirm_signature);
    }
}
