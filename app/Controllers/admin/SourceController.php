<?php
class SourceController extends baseController
{
    private $episodeModel;
    private $moviesModel;
    private $seasonsModel;
    private $activityModel;
    private $sourceModel;
    public function __construct()
    {
        $this->episodeModel = new Episode;
        $this->moviesModel = new Movies;
        $this->seasonsModel = new Season;
        $this->activityModel = new Activity;
        $this->sourceModel = new Source;
    }

    public function list()
    {
        $filter = filterData();
        $movieId = '';
        $seasonId = '';
        $episodeId = '';
        $keyword = '';
        $chuoiWhere = '';

        if (isGet()) {
            if (isset($filter['keyword'])) {
                $keyword = $filter['keyword'];
            }
            if (isset($filter['filter-movie-id'])) {
                $movieId = $filter['filter-movie-id'];
            }
            if (isset($filter['season_id'])) {
                $seasonId = $filter['season_id'];
            }
            if (isset($filter['episode_id'])) {
                $episodeId = $filter['episode_id'];
            }

            // Filter theo movie
            if (!empty($movieId)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "e.movie_id = '$movieId'";
            }

            // Filter theo season
            if (!empty($seasonId)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "e.season_id = '$seasonId'";
            }

            // Filter theo episode
            if (!empty($episodeId)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "vs.episode_id = '$episodeId'";
            }

            // Filter theo keyword (tìm trong tên tập, tên phim, hoặc URL)
            $cleanKeyword = addslashes($keyword);
            if (!empty($keyword)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "(e.name LIKE '%$cleanKeyword%' OR m.tittle LIKE '%$cleanKeyword%' OR vs.url LIKE '%$cleanKeyword%')";
            }
        }

        // Count tổng số records
        $sqlCount = "SELECT vs.*, e.name as episode_name, m.tittle as movie_name, s.name as season_name
        FROM video_sources vs
        LEFT JOIN episodes e ON vs.episode_id = e.id
        LEFT JOIN movies m ON e.movie_id = m.id
        LEFT JOIN seasons s ON e.season_id = s.id
        $chuoiWhere
        ORDER BY vs.created_at DESC";

        $countResult = $this->episodeModel->countAllVideoSources($sqlCount);
        $maxData = $countResult;
        $perPage = 10;
        $maxPage = ceil($maxData / $perPage);
        $page = 1;
        $offset = 0;

        if (isset($filter['page'])) {
            $page = $filter['page'];
        }

        if ($page < 1) {
            $page = 1;
        }
        if ($maxPage > 0 && $page > $maxPage) {
            $page = $maxPage;
        }

        if (isset($page)) {
            $offset = ($page - 1) * $perPage;
        }

        // Lấy dữ liệu với pagination
        $getAllSources = $this->episodeModel->getAllVideoSources("SELECT vs.*, e.name as episode_name, m.tittle as movie_name, s.name as season_name, e.id as episode_id
        FROM video_sources vs
        LEFT JOIN episodes e ON vs.episode_id = e.id
        LEFT JOIN movies m ON e.movie_id = m.id
        LEFT JOIN seasons s ON e.season_id = s.id
        $chuoiWhere
        ORDER BY vs.created_at DESC
        LIMIT $offset, $perPage
        ");

        // Xử lý query string cho pagination
        $queryString = $_SERVER['QUERY_STRING'];
        $queryString = str_replace('&page=' . $page, '', $queryString);
        $queryString = str_replace('page=' . $page, '', $queryString);
        $queryString = trim($queryString, '&');

        $getAllSeasons = $this->seasonsModel->getAllSeason();
        $getAllMovies = $this->moviesModel->getAllMovies();
        $getAllEpisodes = $this->episodeModel->getAllEpisode();
        $filterGet = filterData('get');

        $data = [
            'getAllMovies' => $getAllMovies,
            'getAllSeasons' => $getAllSeasons,
            'getAllEpisodes' => $getAllEpisodes,
            'getAllSources' => $getAllSources,
            'oldData' => $filter,
            'maxPage' => $maxPage,
            'page' => $page,
            'countResult' => $countResult,
            'queryString' => $queryString,
            'filterGet' => $filterGet
        ];

        $this->renderView('/layout-part/admin/source/list', $data);
    }

    public function showEdit()
    {
        $filter = filterData();
        $idSource = $filter['id'];
        $condition = 'id=' . $idSource;
        $getOneSource = $this->sourceModel->getOneSource($condition);
        $idMovie = $getOneSource['movie_id'];
        $idSeason = $getOneSource['season_id'];
        $idEpisode = $getOneSource['episode_id'];
        $data = [
            'oldData' => $getOneSource,
            'idMovie' => $idMovie,
            'idSeason' => $idSeason,
            'idEpisode' => $idEpisode
        ];
        $this->renderView('/layout-part/admin/source/edit', $data);
    }

    public function edit()
    {
        $filter = filterData();
        $idMovie = $filter['movie_id'];
        $idSeason = $filter['season_id'];
        $idEpisode = $filter['episode_id'];
        $data = [
            'voice_type' => $filter['voice_type'],
            'source_url' => $filter['source_url'],
        ];
        $condition = 'id=' . $filter['id'];
        $oldData = $this->sourceModel->getOneSource($condition);
        $checkUpdate = $this->sourceModel->updateVideoSource($data, $condition);
        if ($checkUpdate) {
            // Lặp qua dataUpdate để xem trường nào thay đổi
            $changes = [];
            foreach ($data as $key => $value) {
                if ($oldData[$key] != $value) {
                    $changes[$key] = [
                        'from' => $oldData[$key],
                        'to' => $value
                    ];
                }
            }
            //ghi log
            if (!empty($changes)) {
                $this->activityModel->log(
                    $_SESSION['auth']['id'],
                    'update',
                    'video_source',
                    $filter['id'],
                    $oldData,
                    $data
                );
            }
            setSessionFlash('msg', 'Cập nhật thành công');
            setSessionFlash('msg_type', 'success');
            reload('/admin/source');
        } else {
            setSessionFlash('msg', 'Cập nhật thất bại');
            setSessionFlash('msg_type', 'error');
            reload('/admin/source');
        }
    }

    public function delete()
    {
        $filter = filterData('get');
        $condition = 'id=' . $filter['id'];
        $checkID = $this->sourceModel->getOneSource($condition);
        if (!empty($checkID)) {
            $deleteSource = $this->sourceModel->deleteVideoSource($condition);
            if ($deleteSource) {
                // GHI LOG
                $this->activityModel->log(
                    $_SESSION['auth']['id'],
                    'delete',
                    'video_source',
                    $filter['id'],
                    $checkID, // Lưu data cũ để audit
                    null
                );
                setSessionFlash('msg', 'Xoá nguồn video thành công.');
                setSessionFlash('msg_type', 'success');
                reload('/admin/source');
            } else {
                setSessionFlash('msg', 'Xoá nguồn video thất bại.');
                setSessionFlash('msg_type', 'danger');
                reload('/admin/source');
            }
        }
    }
}
