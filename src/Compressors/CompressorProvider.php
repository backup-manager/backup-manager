<?php namespace BigName\DatabaseBackup\Compressors;

class CompressorProvider
{
    public function get($name)
    {
        if (is_null($name)) {
            return new NullCompressor;
        }

        $class = $this->getClassName($name);
        if ( ! class_exists($class)) {
            throw new CompressorTypeNotSupported('The requested compressor type "' . $class . '" is not currently supported.');
        }
        return new $class;
    }

    private function getClassName($type)
    {
        $type = ucfirst(strtolower($type));
        return "BigName\\DatabaseBackup\\Compressors\\{$type}Compressor";
    }

} 
