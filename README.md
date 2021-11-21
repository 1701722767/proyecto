
# Deploy in server

1. Enter with ssh to server and go to `/home/u497810042/goodwind/scripts` path

### For API
1. Check that your changes are in `main` branch
2. Run in the server:
```
	bash deploy_api.sh
```
3. Execute all command necessaries for create migrations or other artifacts

### For Dashboard
1. build the dist for this run in the local machine:
```
     npm run build
```
2. Create the .zip with content of `dashboard/dist`
3. Upload this file to server and save in `/home/u497810042/public_html`
4. Run in the server
```
	bash deploy_dashboard.sh
```

> For this deploy yo can do roolback if this is necesary only follow the script