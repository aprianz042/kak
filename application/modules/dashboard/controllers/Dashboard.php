class Dashboard extends Authenticated_Controller {

    public function index()
    {
        $this->load->view('dashboard_view');
    }
}
