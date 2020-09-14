<?php


namespace App\Form\Transformer;


use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EstimationTransformer implements DataTransformerInterface
{
    const TIME_UNITS = ['d' => 1440, 'h' => 60];

    /**
     * @param double $value
     * @return string
     */
    public function transform($value)
    {
        if ($value/1440 >= 1)
        {
            return round($value/1440, 2).'d';
        }
        elseif ($value/60 >= 1)
        {
            return round($value/60, 2).'h';
        }
        else
        {
            return round($value, 2).'m';
        }
    }

    /**
     * @param string $value
     * @return double
     */
    public function reverseTransform($value)
    {
        $splitted_values = preg_split('#(?<=\d)(?=[a-z])#i', $value);
        try {
            $estimation = (double) $splitted_values[0];
            $time_unit = $splitted_values[1];
        }
        catch (\Exception $e)
        {
            throw new TransformationFailedException('wrong estimation format');
        }

        if (!$estimation || !$time_unit || !array_key_exists($time_unit, $this::TIME_UNITS))
        {
            throw new TransformationFailedException('wrong estimation format');
        }
        return $estimation * $this::TIME_UNITS[$time_unit];
    }
}