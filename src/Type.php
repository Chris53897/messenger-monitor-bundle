<?php

/*
 * This file is part of the zenstruck/messenger-monitor-bundle package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\Messenger\Monitor;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @template T of object = object
 */
final class Type implements \Stringable
{
    /** @var class-string<T> */
    private string $class;

    /** @var T */
    private object $object;

    /**
     * @internal
     *
     * @param class-string<T>|T $value
     */
    public function __construct(string|object $value, private ?string $description = null)
    {
        if ($value instanceof \Throwable && !$this->description) {
            $this->description = $value->getMessage();
        }

        if (\is_object($value)) {
            $this->object = $value;
            $value = $value::class;
        }

        $this->class = $value;
    }

    public function __toString(): string
    {
        return $this->class;
    }

    /**
     * @return class-string<T>
     */
    public function class(): string
    {
        return $this->class;
    }

    /**
     * @return T|null
     */
    public function object(): ?object
    {
        return $this->object ?? null;
    }

    public function shortName(): string
    {
        return \str_contains($this->class, '\\') ? \mb_substr($this->class, \mb_strrpos($this->class, '\\') + 1) : $this->class;
    }

    public function description(): ?string
    {
        if ($this->description) {
            return $this->description;
        }

        if (!isset($this->object)) {
            return null;
        }

        return $this->description = $this->object instanceof \Stringable ? (string) $this->object : null;
    }
}
