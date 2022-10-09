import React, { Component, Fragment } from 'react';
import { ajaxurl, awesomecoder, headers, licence_key, metaFields,modified_date,post_id,states, uploads } from './Backend';
import { BackspaceIcon, ExclamationCircleIcon, FolderAddIcon, FolderDownloadIcon, FolderIcon, FolderOpenIcon, RefreshIcon, SaveAsIcon, SaveIcon } from '@heroicons/react/outline';
import axios from 'axios';
import { bind, isArray, isObject } from 'lodash';

class Metabox extends Component {

    constructor(props) {
        super(props)

        this.state = {
            refresh: false,
            featch: "",
			awesomecoder_app_icon: states?.awesomecoder_app_icon,
			awesomecoder_app_downloads: states?.awesomecoder_app_downloads,
			awesomecoder_app_stars: states?.awesomecoder_app_stars,
			awesomecoder_app_ratings: states?.awesomecoder_app_ratings,
			awesomecoder_app_devName: states?.awesomecoder_app_devName,
			awesomecoder_app_devLink: states?.awesomecoder_app_devLink,
			awesomecoder_app_compatible_with: states?.awesomecoder_app_compatible_with,
			awesomecoder_app_size: states?.awesomecoder_app_size,
			awesomecoder_app_last_version: states?.awesomecoder_app_last_version,
			awesomecoder_app_link: states?.awesomecoder_app_link,
			awesomecoder_app_price: states?.awesomecoder_app_price,
			uploads: uploads,
			currentFile: null,
			fallBack: false,
			activating: false,
			key: licence_key,
			notice: false,
        }

        this.uploadFileRef = React.createRef();
        this.activateRef = React.createRef();

        this.handleFeatchData = this.handleFeatchData.bind(this);
        this.handleChange = this.handleChange.bind(this);
    }

    componentDidMount = () => {
        this.handleCheckLicence()
    }

    handleFeatchData = () => {
        const theTitle = document.querySelector("h1.wp-block.wp-block-post-title");
        const title = document.getElementById("title");

        const self = this;
        self.setState({refresh : true});

        axios.post(ajaxurl, {
          app: self.state?.featch,
        })
        .then(function (res) {
          const response = res.data;
            self.setState({
                awesomecoder_app_icon : response?.icon,
                awesomecoder_app_downloads : response?.downloads,
                awesomecoder_app_stars : response?.stars,
                awesomecoder_app_ratings : response?.ratings,
                awesomecoder_app_devName : response?.devName,
                awesomecoder_app_devLink : response?.devLink,
            })
            theTitle.textContent=response?.name;
            if(title){
                theTitle.value=response?.name;
            }

            if(response?.name !==""){
                self.setState({
                    awesomecoder_app_link: self.state?.featch,
                })
            }

            self.setState({refresh : false})
        })
        .catch(function (err) {
            self.setState({refresh : false})
        });
    }

    handleChangeVersion = (e,upload) =>{
        const newUploads = this.state.uploads;
        newUploads[upload].version = e.target.value;
        this.setState({
            uploads: newUploads
        })
    }

    handleChange =(name,event) => {
        //more logic here as per the requirement
        this.setState({
            [name]: event.target.value,
        });
    };

    handleAddUpload = (e) =>{
        const newUploads = this.state.uploads;
        newUploads.push({
            version: "",
            file: "",
            size: ""
        })
        this.setState( prevState => ({
            uploads: newUploads
        }))

        // this.setState( prevState => ({
        //     uploads: {
        //         ...prevState.uploads,        // copy all other key-value pairs of food object
        //         [1]:{
        //             version: "0.sdfsdf.0",
        //             file: "sdfsdf.apk",
        //             size: "sdf"
        //         }
        //         // pizza: {                     // specific object of food object
        //         //     ...prevState.food.pizza,   // copy all pizza key-value pairs
        //         //     extraCheese: true          // update value of specific key
        //         //   }
        //     }
        // }))
    }

    handleRemoveUpload = (e,upload) =>{
        const newUploads = this.state.uploads.filter((item,i) => i != upload);
        this.setState({
            uploads: newUploads,
        })
    }

    handleUploadClick = (e,upload) =>{
        this.setState({
            currentFile: upload
        })
        this.uploadFileRef.current.click();
    }

    handleUploadChange = (e) =>{
        const file = e.target.files[0];

        if(file){
            // Create an object of formData
            const formData = new FormData();
            // Update the formData object
            formData.append(
              "apk",
              file,
              file.name
            );
            const fileId = this.state.currentFile;
            const fileObj = this.state.uploads[fileId];

            axios.post(`${ajaxurl}&awesomecoder_action=upload_apk&post_id=${post_id}&var=${fileObj.version}`,formData,headers).then((res)=>{
                const response = res.data;
                if(response.success){
                    const newUploads = this.state.uploads;
                    newUploads[fileId].file = response.name;
                    newUploads[fileId].size = response.size;
                    this.setState({
                        uploads: newUploads
                    })
                }
            })
        }
    }

    handleActivateLicence = (e) =>{
        const self = this;
        const lic = this.activateRef.current.value;
        self.setState({
            activating: true
        })
        axios.post(`${ajaxurl}&awesomecoder_action=lic`,{
            key: lic
        },headers).then((res)=>{
            if(res.data.success){
                self.setState({
                    key: res.data.key,
                    notice: false,
                })
                self.handleCheckLicence();
            }else{
                self.setState({
                    fallBack: true,
                    key: "Please use a valid licence key.",
                    notice: true,
                })
            }

            self.setState({
                activating: false
            })
        })
    }

    handleCheckLicence = (e) =>{
        const self = this;
        axios.get(`https://localhost/wordpress/wp-json/awesomecoder/v1/verify/?key=${self.state.key}`)
        .then((res)=>{
            if(res.data.success){
                self.setState({
                    fallBack: false,
                    notice: false,
                })
            }else{
                self.setState({
                    fallBack: true,
                    notice: true,
                    key: "Please use a valid licence key."
                })
            }
        })
    }

    render() {
        return (
            <div className='relative max-w-7xl mx-auto'>
                {this.state.fallBack ?
                    <Fragment>
                        <div className="w-full flex justify-center items-center md:h-40 h-32 rounded-md shadow-md border border-slate-300/50">
                            <div className='relative flex justify-center flex-col space-y-2'>
                                <span className='animate-pulse mx-auto text-sm font-semibold flex items-center'><ExclamationCircleIcon className='mr-2 h-8 w-8 text-red-500' /> Don't have access.</span>
                                <div className="relative flex justify-center">
                                    {this.state.activating ?
                                        <button type="button" className="w-full max-w-xs flex justify-center items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-primary-300 hover:bg-primary-300 transition ease-in-out duration-150 cursor-not-allowed" disabled="">
                                            <div className="relative inline-flex justify-center items-center">
                                                <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Processing...
                                            </div>
                                        </button>
                                        :
                                        <Fragment>
                                            <input
                                                ref={this.activateRef}
                                                type="text"
                                                name="activate"
                                                id='activate'
                                                value={this.state.key}
                                                onChange={(e)=> this.setState({key: e.target.value})}
                                                className={` ${this.state.notice ? "currentNotice" : "currentValidNotice" } block p-3 rounded-r-none border-gray-300/10 shadow-xl transition-all duration-200 sm:text-sm sm:leading-5 rounded-md `}
                                                placeholder='Email or Licence Key'
                                            />
                                            <button onClick={(e)=> this.handleActivateLicence(e)} className='p-2 bg-primary-400 rounded-r-md text-white'>Activate</button>
                                        </Fragment>

                                    }

                                </div>
                            </div>
                        </div>
                    </Fragment>
                    :
                    // start::main
                    <Fragment>
                        <div className="full flex relative my-1 justify-between">
                            <input
                                onChange={event => this.handleChange( "featch", event)}
                                value={this.state?.featch}
                                placeholder="PlayStore App Url" type="text"
                                className="awesomecoder_app_url block w-screen p-3 border-gray-300/10 shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 focus:border-primary-300/0 focus:ring focus:ring-primary-200/0 focus:ring-opacity-50" />
                            <span onClick={this.handleFeatchData} className="bg-primary-400 flex justify-around items-center w-1/5 cursor-pointer rounded-r-md">
                                <span className="md:block hidden text-white font-semibold text-sm pointer-events-none ">
                                    Featch Data
                                </span>
                                <RefreshIcon className={ this.state?.refresh ? "animate-spin pointer-events-none h-6 w-6 text-white font-semibold text-sm" : "pointer-events-none h-6 w-6 text-white font-semibold text-sm" } />
                            </span>
                        </div>
                        <div className="grid 2xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 gird-cols-1">
                            {metaFields.map((field, i) => {
                                return(
                                    <div key={field?.name} className="full relative my-1">
                                        <div className="relative rounded-md ">
                                            <p className="mb-1 italic text-slate-800 text-xs font-light">
                                                {field?.label}
                                            </p>
                                            <input onChange={event => this.handleChange( field?.name, event)}
                                            placeholder={field?.placeholder}
                                            value={this.state[field?.name]}
                                            type={ field?.type}
                                            name={field?.name}
                                            className={` ${field?.disabled && "opacity-70 pointer-events-none"} block p-3 lg:mx-0 md:mx-auto border-gray-300/10 shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 rounded-md `} />
                                        </div>
                                    </div>
                                )
                            })}
                            <div className="full relative my-1">
                                <div className="relative rounded-md ">
                                    <p className="mb-1 italic text-slate-800 text-xs font-light">
                                        Updated
                                    </p>
                                    <input
                                    type={"text"}
                                    disabled
                                    value={modified_date}
                                    className="block p-3 lg:mx-0 md:mx-auto border-gray-300/10 shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 rounded-md " />
                                </div>
                            </div>
                        </div>
                        <div className="relative w-full rounded-md border-slate-300/30 my-2 border flex p-3 justify-between items-center">
                            <span className='text-xs font-semibold'>Upload Apps</span>
                            <span className=' cursor-pointer '
                                onClick={(e)=> this.handleAddUpload(e)}
                            >
                                <FolderAddIcon strokeWidth={1.5} className='w-6 h-6 pointer-events-none mr-2' />
                            </span>
                        </div>
                        <div className="grid lg:grid-cols-3 md:grid-cols-2 gird-cols-1 my-4 gap-3 ">
                            {Object.keys(this.state.uploads).map((upload,i)=> {
                                const app = this.state.uploads[upload];
                                return(
                                    <Fragment key={i}>
                                        <div className="relative w-full rounded-md border border-slate-300/30 shadow-md p-4 space-y-3">
                                            <div className="flex justify-between items-center">
                                                {/* <span className='text-sm text-slate-600 font-semibold truncate pr-2'>{ app?.file.slice(0, -4) }</span> */}
                                                <span className='text-sm text-slate-600 font-semibold truncate pr-2'>{ app.file && app.file.slice(0, -4) }</span>
                                                <span className='cursor-pointer' onClick={(e)=> this.handleRemoveUpload(e, upload)}>
                                                    <BackspaceIcon className='pointer-events-none w-5 h-5 text-red-400' />
                                                </span>
                                            </div>
                                            <div className="relative rounded-md cursor-pointer"
                                                onClick={(e)=> this.handleUploadClick(e,upload)}
                                            >
                                                <FolderDownloadIcon className="absolute pointer-events-none right-2 top-2 h-5 w-5" />
                                                <input type="hidden" name={`awesomecoder_file_name[${upload}]`} value={app.file && app?.file} />
                                                <input
                                                type={"text"}
                                                disabled
                                                placeholder="Choose File"
                                                value={app.file && app?.file}
                                                style={{ width: "100%" }}
                                                onChange={(e)=> console.log(e)}
                                                className="block pl-5 p-3 pointer-events-none border-gray-300/10 shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 rounded-md " />
                                            </div>
                                            <div className="relative rounded-md ">
                                                <input type="hidden" name={`awesomecoder_file_size[${upload}]`} value={app?.size} />
                                                <input
                                                type={"text"}
                                                placeholder="Size"
                                                disabled
                                                style={{ width: "100%" }}
                                                value={app?.size}
                                                onChange={(e)=> console.log(e)}
                                                className="block p-3 border-gray-300/10 shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 rounded-md " />
                                            </div>
                                            <div className="relative rounded-md ">
                                                <input
                                                name={`awesomecoder_file_version[${upload}]`}
                                                type={"text"}
                                                placeholder="Version"
                                                value={app.version && app?.version}
                                                style={{ width: "100%" }}
                                                onChange={(e)=> this.handleChangeVersion(e,upload)}
                                                className="block p-3 border-gray-300/10 shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 rounded-md " />
                                            </div>
                                        </div>
                                    </Fragment>
                                )
                            })}
                            <input type="file" className='absolute left-0 -z-10 opacity-0 pointer-events-none' onChange={(e)=> this.handleUploadChange(e)} name="apk" ref={this.uploadFileRef} />
                        </div>
                    </Fragment>
                    // end::main
                }

            </div>
        );
    }
}

export default Metabox;
