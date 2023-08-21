<?php

namespace Helper;

class Logger
{
	private static array $entries = [];

	public static function addEntry(string $message): void
	{
		self::$entries[] = $message;
	}

	public static function getEntries(): array
	{
		return self::$entries;
	}
}
