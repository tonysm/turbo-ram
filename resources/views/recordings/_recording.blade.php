@include(sprintf('recordings._%s', str(class_basename($recording->recordable))->snake()), [
    'recording' => $recording,
])
