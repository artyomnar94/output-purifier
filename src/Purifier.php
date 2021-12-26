<?php

namespace Output;

/**
 * @property string[] $allowedHtmlTags - list of allowed html tags. @see https://developer.mozilla.org/ru/docs/Web/HTML/Element
 * @property string[] $allowedHosts - list of allowed hosts for downloading resources/redirecting
 * @property string $defaultUrl - URL for replacement disallowed hosts
 */
class Purifier
{
	private static ?self $instance = null;

	private function __construct(
		public array $allowedHtmlTags,
		public array $allowedHosts,
		public string $defaultUrl
	)
	{
	}

	public static function getInstance(
		array $allowedHtmlTags = ['div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'ol', 'ul', 'li', 'br'],
		array $allowedHosts = [],
		string $defaultUrl = '#'
	): self
	{
		if (is_null(self::$instance)) {
			self::$instance = new Purifier($allowedHtmlTags, $allowedHosts, $defaultUrl);
		}
		return self::$instance;
	}

	/**
	 * @param string $rawString - external string value for saving/displaying/providing
	 * @return string
	 */
	public function filter(string $rawString): string
	{
		$pureTagString = $this->clearTags($rawString);
		if ($this->allowedHosts) {
			return $this->clearUrls($pureTagString);
		}
		return $pureTagString;
	}

	/**
	 * Removes disallowed html tags
	 * @param string $rawString
	 * @return string
	 */
	private function clearTags(string $rawString): string
	{
		return strip_tags($rawString, $this->allowedHtmlTags);
	}

	/**
	 * Removes disallowed hosts
	 * @param string $pureTagString
	 * @return string
	 */
	private function clearUrls(string &$pureTagString): string
	{
		$validHost = $this->allowedHosts;
		$defaultUrl = $this->defaultUrl;
		return preg_replace_callback(
			"/http(s)?:\/\/(w{3}\.)?[^:\/\"'\\\]{2,252}\.\w{2,5}/",
			function ($matches) use ($validHost, $defaultUrl): string {
				return (!in_array($matches[0], $validHost)) ? $defaultUrl : $matches[0];
			},
			$pureTagString
		);
	}
}