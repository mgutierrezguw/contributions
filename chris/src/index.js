import { TextControl, PanelBody, PanelRow, ColorPicker, SelectControl } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
import "./index.css";

wp.blocks.registerBlockType("myplugin/lorem-custom-block", {
    title: "Lorem Custom Button",
    icon: "button",
    category: "common",
    attributes: {
        buttonText: {type: "string", default: "My Button"},
        buttonFontSize: {type: "string", default: "16"},
        buttonFontColor: {type: "string", default: "#000"},
        buttonLeftColor: {type: "string", default: "#fff"},
        buttonRightColorOne: {type: "string", default: "#EBEBEB"},
        buttonRightColorTwo: {type: "string", default: "#EBEBEB"},
        buttonWidth: {type: "string", default: "262"},
        buttonHeight: {type: "string", default: "44"},
        buttonIcon: {type: "string"},
        buttonIconSize: {type: "string", default: "15"},
        buttonIconColor: {type: "string", default: "#fff"},

    },
    
    edit: function(props) {
       const { buttonText, buttonLeftColor, buttonRightColorOne, buttonRightColorTwo, buttonWidth, buttonHeight, buttonIcon, buttonIconColor, buttonIconSize, buttonFontSize, buttonFontColor, } = props.attributes;
        //returns what is seen in the editor
        return (
            <div>
                <InspectorControls>
                    <PanelBody title="Button Colors - Left Side" initialOpen={true}>
                        <PanelRow>
                            <ColorPicker color={buttonLeftColor}  onChangeComplete={e => props.setAttributes({buttonLeftColor: e.hex})}/>
                        </PanelRow>
                    </PanelBody>
                    <PanelBody title="Button Colors - Right Side 1" initialOpen={false}>
                        <PanelRow>
                            <ColorPicker color={buttonRightColorOne}  onChangeComplete={e => props.setAttributes({buttonRightColorOne: e.hex})}/>
                        </PanelRow>
                    </PanelBody>
                    <PanelBody title="Button Colors - Right Side 2" initialOpen={false}>
                        <PanelRow>
                            <ColorPicker color={buttonRightColorTwo}  onChangeComplete={e => props.setAttributes({buttonRightColorTwo: e.hex})}/>
                        </PanelRow>
                    </PanelBody>
                    <PanelBody>
                        <PanelRow>
                            <TextControl
                                label="Button Width px"
                                onChange={ e => props.setAttributes({buttonWidth: e})}
                                value={buttonWidth}
						    />
                        </PanelRow>
                        <PanelRow>
                            <TextControl
                                label="Button Height px"
                                onChange={ e => props.setAttributes({buttonHeight: e})}
                                value={buttonHeight}
						    />
					    </PanelRow>
                    </PanelBody>
                    <PanelBody>
                        <PanelRow>
                            <SelectControl
                                label="Button Icon"
                                value={buttonIcon}
                                options={ [
                                    { label: '', value: '' },
                                    { label: 'Arrow', value: 'icon-btn-arrow' },
                                    { label: 'Thumbs Up', value: 'icon-btn-thumbup' },
                                    { label: 'Download', value: 'icon-btn-download' },
                                ] }
                                onChange={e => props.setAttributes({buttonIcon: e}) }
                                __nextHasNoMarginBottom
                            />
                        </PanelRow>
                        <PanelRow>
                            <TextControl
                                label="Icon Size px"
                                onChange={ e => props.setAttributes({buttonIconSize: e})}
                                value={buttonIconSize}
						    />
					    </PanelRow>
                    </PanelBody>
                    <PanelBody title="Icon Color" initialOpen={false}>
                            <PanelRow>
                                <ColorPicker color={buttonIconColor}  onChangeComplete={e => props.setAttributes({buttonIconColor: e.hex})}/>
                            </PanelRow>
                    </PanelBody>
                    <PanelBody>
                        <PanelRow>
                            <TextControl
                                label="Button Text"
                                onChange={ e => props.setAttributes({buttonText: e})}
                                value={buttonText}
						    />
					    </PanelRow>
                        <PanelRow>
                            <TextControl
                                label="Font Size px"
                                onChange={ e => props.setAttributes({buttonFontSize: e})}
                                value={buttonFontSize}
						    />
					    </PanelRow>
                    </PanelBody>
                    <PanelBody title="Font Color" initialOpen={false}>
                        <PanelRow>
                            <ColorPicker color={buttonFontColor}  onChangeComplete={e => props.setAttributes({buttonFontColor: e.hex})}/>
                        </PanelRow>
                    </PanelBody>
                </InspectorControls>
                <button className="lorem-edit-btn flex-row flex-center" style={{borderColor: buttonRightColorOne, width: `${buttonWidth}px`, height: `${buttonHeight}px`}}>
                    <div className='lorem-edit-btn-left flex-center' style={{backgroundColor: buttonLeftColor, fontSize: `${buttonIconSize}px`, color: buttonIconColor}}>
                        <div className='lorem-edit-btn-triangle' style={{borderLeftColor: buttonLeftColor}}></div>
                        <span className={buttonIcon}></span>
                    </div>
                    <div className='lorem-edit-btn-right flex-center' style={{color: buttonFontColor, background: `linear-gradient(${buttonRightColorTwo}, ${buttonRightColorOne})`, fontSize: `${buttonFontSize}px`}}>
                        {buttonText}
                    </div>
                </button>
            </div>
        );
    },
    save: function(props) {
        //use php to render output based on the namspace "myplugin/lorem-custom-block" in the index.php
        return null;
    },
})