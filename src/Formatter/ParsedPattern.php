<?php

namespace CommerceGuys\Intl\Formatter;

/**
 * Represents a parsed number pattern.
 */
final class ParsedPattern
{
    /**
     * The positive number pattern.
     *
     * @var string
     */
    protected $positivePattern;

    /**
     * The negative number pattern.
     *
     * @var string
     */
    protected $negativePattern;

    /**
     * Whether grouping is used.
     *
     * @var bool
     */
    protected $groupingUsed;

    /**
     * The primary group size.
     *
     * @var int
     */
    protected $primaryGroupSize;

    /**
     * The secondary group size.
     *
     * @var int
     */
    protected $secondaryGroupSize;

    /**
     * Creates a new ParsedPattern instance.
     *
     * @param string $pattern The raw pattern.
     */
    public function __construct($pattern)
    {
        // Split the pattern into positive and negative patterns.
        $patternList = explode(';', $pattern);
        if (!isset($patternList[1])) {
            // No explicit negative pattern was provided, construct it.
            $patternList[1] = '-' . $patternList[0];
        }

        $this->positivePattern = $patternList[0];
        $this->negativePattern = $patternList[1];
        $this->groupingUsed = (strpos($patternList[0], ',') !== false);
        if ($this->groupingUsed) {
            preg_match('/#+0/', $patternList[0], $primaryGroupMatches);
            $this->primaryGroupSize = $this->secondaryGroupSize = strlen($primaryGroupMatches[0]);
            $numberGroups = explode(',', $patternList[0]);
            if (count($numberGroups) > 2) {
                // This pattern has a distinct secondary group size.
                $this->secondaryGroupSize = strlen($numberGroups[1]);
            }
        }
    }

    /**
     * Gets the positive number pattern.
     *
     * Used to format positive numbers.
     *
     * @return string
     */
    public function getPositivePattern()
    {
        return $this->positivePattern;
    }

    /**
     * Gets the negative number pattern.
     *
     * Used to format negative numbers.
     *
     * @return string
     */
    public function getNegativePattern()
    {
        return $this->negativePattern;
    }

    /**
     * Gets whether grouping is used.
     *
     * Indicates that major digits should be grouped according to
     * group sizes, right-to-left.
     *
     * @return bool
     */
    public function isGroupingUsed()
    {
        return $this->groupingUsed;
    }

    /**
     * Gets the primary group size.
     *
     * @return int|null
     */
    public function getPrimaryGroupSize()
    {
        return $this->primaryGroupSize;
    }

    /**
     * Gets the secondary group size.
     *
     * @return int|null
     */
    public function getSecondaryGroupSize()
    {
        return $this->secondaryGroupSize;
    }
}
