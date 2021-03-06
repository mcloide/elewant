<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\Herd\HerdId;
use PhpSpec\ObjectBehavior;

final class AdoptElePHPantSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->beConstructedThrough('byHerd', ['00000000-0000-0000-0000-000000000000', Breed::RED_LARAVEL_LARGE]);
        $this->shouldHaveType(AdoptElePHPant::class);
        $this->herdId()->shouldEqual(HerdId::fromString('00000000-0000-0000-0000-000000000000'));
        $this->breed()->shouldEqual(Breed::redLaravelLarge());
    }
}
