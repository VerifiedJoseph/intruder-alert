<?php

namespace Helper;

final class Misc
{
	/**
	 * Detect internet protocol address version
	 * 
	 * @param string $address IP address
	 * @return int IP version
	 */
	static public function detectIpVersion(string $address): int
	{
		if (str_contains(':', $address) === true) {
			return 6;
		}

		return 4;
	}
}
