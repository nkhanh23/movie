<?php
class Source extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getOneSource($condition)
    {
        return $this->getOne("SELECT * FROM video_sources WHERE $condition");
    }

    public function insertVideoSource($data)
    {
        return $this->insert('video_sources', $data);
    }

    public function updateVideoSource($data, $condition)
    {
        return $this->update('video_sources', $data, $condition);
    }

    public function deleteSource($condition)
    {
        return $this->delete('video_sources', $condition);
    }

    public function deleteVideoSource($condition)
    {
        return $this->delete('video_sources', $condition);
    }
}
