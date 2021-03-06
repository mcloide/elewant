<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Breed;

use PhpSpec\ObjectBehavior;
use Traversable;

final class BreedCollectionSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedThrough('fromArray', [[]]);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(BreedCollection::class);
    }

    public function it_is_empty_without_breeds(): void
    {
        $this->isEmpty()->shouldReturn(true);
    }

    public function it_is_not_empty_with_a_breed(): void
    {
        $this->add(Breed::fromString(Breed::WHITE_CONFOO_LARGE));
        $this->isEmpty()->shouldReturn(false);
    }

    public function it_can_be_counted(): void
    {
        $confoo = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $amsterdamPHP = Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR);
        $this->add($confoo);
        $this->add($amsterdamPHP);

        $this->count()->shouldReturn(2);
    }

    public function it_can_be_iterated_over(): void
    {
        $confoo = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $amsterdamPHP = Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR);
        $this->add($confoo);
        $this->add($amsterdamPHP);

        $this->getIterator()->shouldImplement(Traversable::class);
    }

    public function it_adds_breeds(): void
    {
        $confoo = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $amsterdamPHP = Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR);
        $this->add($confoo);
        $this->add($amsterdamPHP);

        $expected = BreedCollection::fromArray(
            [
                $confoo,
                $amsterdamPHP,
            ]
        );

        $this->equals($expected)->shouldReturn(true);
    }

    public function it_removes_breeds(): void
    {
        $confoo = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $amsterdamPHP = Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR);
        $zf2 = Breed::fromString(Breed::GREEN_ZF2_LARGE);

        $initialCollection = BreedCollection::fromArray(
            [
                $confoo,
                $amsterdamPHP,
                $zf2,
            ]
        );

        $this->add($confoo);
        $this->add($amsterdamPHP);
        $this->add($zf2);
        $this->equals($initialCollection)->shouldReturn(true);

        $expected = BreedCollection::fromArray(
            [
                $confoo,
                $amsterdamPHP,
            ]
        );

        $this->remove($zf2);
        $this->equals($expected)->shouldReturn(true);
    }

    public function it_knows_when_it_contains_a_specific_breed(): void
    {
        $confoo = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $amsterdamPHP = Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR);
        $this->add($confoo);
        $this->add($amsterdamPHP);

        $this->contains($confoo)->shouldReturn(true);
        $this->contains($amsterdamPHP)->shouldReturn(true);
    }

    public function it_knows_when_it_does_not_contain_a_specific_breed(): void
    {
        $this->contains(Breed::fromString(Breed::WHITE_CONFOO_LARGE));
    }

    public function it_does_not_add_the_same_breed_more_than_once(): void
    {
        $confoo = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $this->add($confoo);
        $this->add($confoo);
        $this->add($confoo);

        $expected = BreedCollection::fromArray(
            [
                $confoo,
            ]
        );

        $this->equals($expected)->shouldReturn(true);
    }

    public function it_equals_another_empty_breedcollection(): void
    {
        $other = BreedCollection::fromArray([]);
        $this->equals($other)->shouldReturn(true);
    }

    public function it_equals_another_similar_breedcollection(): void
    {
        $other = BreedCollection::fromArray(
            [
                Breed::fromString(Breed::WHITE_CONFOO_LARGE),
                Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR),
            ]
        );

        $this->add(Breed::fromString(Breed::WHITE_CONFOO_LARGE));
        $this->add(Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR));

        $this->equals($other)->shouldReturn(true);
    }

    public function it_does_not_equal_a_different_breedcollection(): void
    {
        $other = BreedCollection::fromArray(
            [
                Breed::fromString(Breed::WHITE_CONFOO_LARGE),
            ]
        );

        $this->add(Breed::fromString(Breed::WHITE_CONFOO_LARGE));
        $this->add(Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR));

        $this->equals($other)->shouldReturn(false);
    }

    public function it_merges_with_another_breedcollection(): void
    {
        $confoo = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $amsterdamPHP = Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR);
        $zf2 = Breed::fromString(Breed::GREEN_ZF2_LARGE);

        $other = BreedCollection::fromArray(
            [
                $confoo,
                $amsterdamPHP,
                $zf2,
            ]
        );

        $expected = BreedCollection::fromArray(
            [
                $confoo,
                $amsterdamPHP,
                $zf2,
            ]
        );

        $this->add($confoo);
        $this->merge($other);
        $this->equals($expected)->shouldReturn(true);
    }

    public function it_is_missing_breeds_when_compared_to_a_different_breedcollection(): void
    {
        $confoo = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $amsterdamPHP = Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR);
        $zf2 = Breed::fromString(Breed::GREEN_ZF2_LARGE);

        $other = BreedCollection::fromArray(
            [
                $confoo,
                $amsterdamPHP,
                $zf2,
            ]
        );

        $expected = BreedCollection::fromArray(
            [
                $amsterdamPHP,
                $zf2,
            ]
        );

        $this->add($confoo);
        $actualCollection = $this->isMissingBreedsWhenComparedTo($other);
        $actualCollection->equals($expected)->shouldReturn(true);
    }

    public function it_does_not_missing_any_breeds_when_compared_to_a_similar_breedcollection(): void
    {
        $confoo = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $amsterdamPHP = Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR);
        $zf2 = Breed::fromString(Breed::GREEN_ZF2_LARGE);

        $other = BreedCollection::fromArray(
            [
                $confoo,
                $amsterdamPHP,
                $zf2,
            ]
        );

        $expected = BreedCollection::fromArray([]);

        $this->add($confoo);
        $this->add($amsterdamPHP);
        $this->add($zf2);
        $actualCollection = $this->isMissingBreedsWhenComparedTo($other);
        $actualCollection->equals($expected)->shouldReturn(true);
    }

    public function it_has_breeds_in_common_with_an_overlapping_breedcollection(): void
    {
        $confoo = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $amsterdamPHP = Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR);
        $zf2 = Breed::fromString(Breed::GREEN_ZF2_LARGE);

        $other = BreedCollection::fromArray(
            [
                $confoo,
                $amsterdamPHP,
                $zf2,
            ]
        );

        $expected = BreedCollection::fromArray(
            [
                $confoo,
            ]
        );

        $this->add($confoo);
        $actualCollection = $this->hasBreedsInCommonWith($other);
        $actualCollection->equals($expected)->shouldReturn(true);
    }

    public function it_has_no_breeds_in_common_with_a_non_overlapping_breedcollection(): void
    {
        $confoo = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $amsterdamPHP = Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR);
        $zf2 = Breed::fromString(Breed::GREEN_ZF2_LARGE);

        $other = BreedCollection::fromArray(
            [
                $confoo,
                $amsterdamPHP,
            ]
        );

        $expected = BreedCollection::fromArray([]);

        $this->add($zf2);
        $actualCollection = $this->hasBreedsInCommonWith($other);
        $actualCollection->equals($expected)->shouldReturn(true);
    }

    public function it_returns_all_breeds(): void
    {
        $this->beConstructedThrough('all');
        $this->shouldHaveType(BreedCollection::class);
        $this->contains(Breed::fromString(Breed::WHITE_CONFOO_LARGE))->shouldReturn(true);
    }
}
