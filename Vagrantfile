Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu1204"
  config.vm.box_url = "http://cloud-images.ubuntu.com/vagrant/precise/current/precise-server-cloudimg-amd64-vagrant-disk1.box"
  config.vm.provider :virtualbox do |vb|
    vb.customize ["modifyvm", :id, "--memory", "2048"]
    vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    vb.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
    vb.customize ["modifyvm", :id, "--cpus", "2"]
    vb.customize ["setextradata", :id, "VBoxInternal2/SharedFoldersEnableSymlinksCreate/vagrant", "1"]
  end
  config.vm.synced_folder ".", "/vagrant"
  config.vm.provision :shell, :inline => "mount -o remount,noatime /"
  config.vm.provision :shell, :inline => "mountpoint -q /tmp || mount -t tmpfs tmpfs /tmp"
  config.vm.provision :shell, :inline => "mountpoint -q /var/cache || mount -t tmpfs tmpfs /var/cache"
  config.vm.provision "shell", path: "provision.sh"
end
